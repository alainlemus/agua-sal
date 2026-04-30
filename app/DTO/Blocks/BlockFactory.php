<?php

declare(strict_types=1);

namespace App\DTO\Blocks;

use App\Enums\BlockType;
use InvalidArgumentException;

final readonly class BlockFactory
{
    /**
     * @param array{type: string, data: array} $block
     */
    public static function fromArray(array $block): ?BlockDTO
    {
        $type = BlockType::tryFrom($block['type'] ?? '');

        if ($type === null) {
            return null;
        }

        return match ($type) {
            BlockType::Hero => HeroBlockDTO::fromArray($block['data'] ?? []),
            BlockType::FeaturedProducts => FeaturedProductsBlockDTO::fromArray($block['data'] ?? []),
            BlockType::PromotionsCarousel => PromotionsCarouselBlockDTO::fromArray($block['data'] ?? []),
            BlockType::AboutSection => AboutBlockDTO::fromArray($block['data'] ?? []),
            BlockType::BbqSection => BbqSectionBlockDTO::fromArray($block['data'] ?? []),
            BlockType::Faq => FaqBlockDTO::fromArray($block['data'] ?? []),
            BlockType::Pricing => PricingBlockDTO::fromArray($block['data'] ?? []),
            default => null,
        };
    }

    /**
     * @param array<array{type: string, data: array}> $blocks
     * @return array<BlockDTO>
     */
    public static function parseBlocks(array $blocks): array
    {
        $parsed = [];

        foreach ($blocks as $block) {
            $dto = self::fromArray($block);
            if ($dto !== null) {
                $parsed[] = $dto;
            }
        }

        return $parsed;
    }

    public static function getBlockTypes(): array
    {
        return array_map(
            fn(BlockType $type) => ['value' => $type->value, 'label' => $type->label()],
            BlockType::cases()
        );
    }
}