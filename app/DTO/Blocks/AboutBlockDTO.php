<?php

declare(strict_types=1);

namespace App\DTO\Blocks;

use App\Enums\BlockType;

final readonly class AboutBlockDTO extends BlockDTO
{
    public function __construct(
        public string $heading,
        public string $description,
        public ?string $image,
    ) {}

    public static function type(): BlockType
    {
        return BlockType::AboutSection;
    }

    public static function fromArray(array $data): static
    {
        return new self(
            heading: $data['heading'] ?? '',
            description: $data['description'] ?? '',
            image: $data['image'] ?? null,
        );
    }

    protected function data(): array
    {
        return [
            'heading' => $this->heading,
            'description' => $this->description,
            'image' => $this->image,
        ];
    }
}