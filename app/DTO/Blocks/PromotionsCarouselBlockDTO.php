<?php

declare(strict_types=1);

namespace App\DTO\Blocks;

use App\Enums\BlockType;

final readonly class CarouselItemDTO
{
    public function __construct(
        public string $image,
        public ?string $title,
        public ?string $link,
    ) {}

    public static function fromArray(array $data): static
    {
        return new self(
            image: $data['image'] ?? '',
            title: $data['title'] ?? null,
            link: $data['link'] ?? null,
        );
    }
}

final readonly class PromotionsCarouselBlockDTO extends BlockDTO
{
    /**
     * @param CarouselItemDTO[] $items
     */
    public function __construct(
        public string $heading,
        public array $items,
    ) {}

    public static function type(): BlockType
    {
        return BlockType::PromotionsCarousel;
    }

    public static function fromArray(array $data): static
    {
        $items = array_map(
            fn(array $item) => CarouselItemDTO::fromArray($item),
            $data['items'] ?? []
        );

        return new self(
            heading: $data['heading'] ?? 'Noticias y Promociones',
            items: $items,
        );
    }

    protected function data(): array
    {
        return [
            'heading' => $this->heading,
            'items' => array_map(fn($item) => [
                'image' => $item->image,
                'title' => $item->title,
                'link' => $item->link,
            ], $this->items),
        ];
    }
}