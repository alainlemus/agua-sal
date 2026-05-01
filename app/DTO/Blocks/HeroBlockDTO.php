<?php

declare(strict_types=1);

namespace App\DTO\Blocks;

use App\Enums\BlockType;

final readonly class HeroBlockDTO extends BlockDTO
{
    public function __construct(
        public string $heading,
        public string $subheading,
        public ?string $heroImage,
        public ?string $heroVideo,
        public ?string $heroSideImage,
        public ?string $badgeText1,
        public ?string $badgeText2,
    ) {}

    public static function type(): BlockType
    {
        return BlockType::Hero;
    }

    public static function fromArray(array $data): static
    {
        return new self(
            heading: $data['hero_heading'] ?? '',
            subheading: $data['hero_subheading'] ?? '',
            heroImage: $data['hero_image'] ?? null,
            heroVideo: $data['hero_video'] ?? null,
            heroSideImage: $data['hero_side_image'] ?? null,
            badgeText1: $data['hero_badge_text_1'] ?? null,
            badgeText2: $data['hero_badge_text_2'] ?? null,
        );
    }

    protected function data(): array
    {
        return [
            'hero_heading' => $this->heading,
            'hero_subheading' => $this->subheading,
            'hero_image' => $this->heroImage,
            'hero_video' => $this->heroVideo,
            'hero_side_image' => $this->heroSideImage,
            'hero_badge_text_1' => $this->badgeText1,
            'hero_badge_text_2' => $this->badgeText2,
        ];
    }
}