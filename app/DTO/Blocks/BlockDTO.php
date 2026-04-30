<?php

declare(strict_types=1);

namespace App\DTO\Blocks;

use App\Enums\BlockType;
use Illuminate\Support\Collection;

abstract readonly class BlockDTO
{
    abstract public static function type(): BlockType;

    abstract public static function fromArray(array $data): static;

    public static function fromBlockArray(array $block): ?static
    {
        if (($block['type'] ?? '') !== static::type()->value) {
            return null;
        }

        return static::fromArray($block['data'] ?? []);
    }

    public function toArray(): array
    {
        return [
            'type' => $this->type()->value,
            'data' => $this->data(),
        ];
    }

    abstract protected function data(): array;
}