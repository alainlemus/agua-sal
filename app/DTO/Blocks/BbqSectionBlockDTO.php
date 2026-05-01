<?php

declare(strict_types=1);

namespace App\DTO\Blocks;

use App\Enums\BlockType;

final readonly class BbqItemDTO
{
    public function __construct(
        public string $name,
        public ?string $description,
        public ?string $image,
        public ?string $badge,
    ) {}

    public static function fromArray(array $data): static
    {
        return new self(
            name: $data['name'] ?? '',
            description: $data['description'] ?? null,
            image: $data['image'] ?? null,
            badge: $data['badge'] ?? null,
        );
    }
}

final readonly class BbqSectionBlockDTO extends BlockDTO
{
    /**
     * @param BbqItemDTO[] $items
     */
    public function __construct(
        public string $heading,
        public ?string $subheading,
        public ?string $description,
        public ?string $backgroundImage,
        public array $items,
    ) {}

    public static function type(): BlockType
    {
        return BlockType::BbqSection;
    }

    public static function fromArray(array $data): static
    {
        $items = array_map(
            fn(array $item) => BbqItemDTO::fromArray($item),
            $data['items'] ?? []
        );

        return new self(
            heading: $data['heading'] ?? '',
            subheading: $data['subheading'] ?? '',
            description: $data['description'] ?? null,
            backgroundImage: $data['background_image'] ?? null,
            items: $items,
        );
    }

    protected function data(): array
    {
        return [
            'heading' => $this->heading,
            'subheading' => $this->subheading,
            'description' => $this->description,
            'background_image' => $this->backgroundImage,
            'items' => array_map(fn($item) => [
                'name' => $item->name,
                'description' => $item->description,
                'image' => $item->image,
                'badge' => $item->badge,
            ], $this->items),
        ];
    }
}