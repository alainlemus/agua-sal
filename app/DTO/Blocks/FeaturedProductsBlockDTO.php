<?php

declare(strict_types=1);

namespace App\DTO\Blocks;

use App\Enums\BlockType;

final readonly class FeaturedProductsBlockDTO extends BlockDTO
{
    public function __construct(
        public string $heading,
        public string $subtitle,
        public array $productIds,
    ) {}

    public static function type(): BlockType
    {
        return BlockType::FeaturedProducts;
    }

    public static function fromArray(array $data): static
    {
        return new self(
            heading: $data['heading'] ?? 'Platillos Destacados',
            subtitle: $data['subtitle'] ?? 'Descubre lo mejor de nuestra cocina',
            productIds: $data['product_ids'] ?? [],
        );
    }

    protected function data(): array
    {
        return [
            'heading' => $this->heading,
            'subtitle' => $this->subtitle,
            'product_ids' => $this->productIds,
        ];
    }
}