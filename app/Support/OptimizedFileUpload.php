<?php

declare(strict_types=1);

namespace App\Support;

use Filament\Forms\Components\BaseFileUpload;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Exceptions\DecoderException;
use Intervention\Image\ImageManager;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

final class OptimizedFileUpload
{
    private const MAX_WIDTH = 1920;

    private const WEBP_QUALITY = 80;

    private const FAVICON_SIZE = 512;

    public static function register(): void
    {
        // isImportant: true — runs after the component's own setUp(), which
        // otherwise overwrites saveUploadedFileUsing() with its own default.
        BaseFileUpload::configureUsing(
            modifyUsing: function (BaseFileUpload $component): void {
                $component->saveUploadedFileUsing(static fn (BaseFileUpload $component, TemporaryUploadedFile $file): ?string => self::store($component, $file)
                );
            },
            isImportant: true,
        );
    }

    private static function store(BaseFileUpload $component, TemporaryUploadedFile $file): ?string
    {
        try {
            if (! $file->exists()) {
                return null;
            }
        } catch (\Throwable) {
            return null;
        }

        $mime = $file->getMimeType();

        if (! is_string($mime) || ! str_starts_with($mime, 'image/')) {
            return self::storeUnmodified($component, $file);
        }

        try {
            $image = ImageManager::gd()->read($file->getRealPath());
        } catch (DecoderException) {
            // Not a format Intervention can decode (e.g. .ico) — fall back untouched.
            return self::storeUnmodified($component, $file);
        }

        $isFavicon = $component->getName() === 'favicon';

        if ($isFavicon) {
            $image->coverDown(self::FAVICON_SIZE, self::FAVICON_SIZE);
            $extension = 'png';
            $encoded = (string) $image->toPng();
        } else {
            $image->scaleDown(width: self::MAX_WIDTH);
            $extension = 'webp';
            $encoded = (string) $image->toWebp(quality: self::WEBP_QUALITY);
        }

        $directory = trim((string) $component->getDirectory(), '/');
        $filename = Str::ulid() . '.' . $extension;
        $path = $directory !== '' ? "{$directory}/{$filename}" : $filename;

        Storage::disk($component->getDiskName())->put($path, $encoded);

        return $path;
    }

    private static function storeUnmodified(BaseFileUpload $component, TemporaryUploadedFile $file): ?string
    {
        $storeMethod = $component->getVisibility() === 'public' ? 'storePubliclyAs' : 'storeAs';

        return $file->{$storeMethod}(
            $component->getDirectory(),
            $component->getUploadedFileNameForStorage($file),
            $component->getDiskName(),
        );
    }
}
