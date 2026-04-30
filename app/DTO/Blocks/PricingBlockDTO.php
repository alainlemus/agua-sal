<?php

declare(strict_types=1);

namespace App\DTO\Blocks;

use App\Enums\BlockType;

final readonly class PricingPlanDTO
{
    public function __construct(
        public string $name,
        public string $price,
        public ?string $description,
        public ?string $features,
        public bool $highlighted,
        public ?string $accentColor,
    ) {}

    public static function fromArray(array $data): static
    {
        return new self(
            name: $data['name'] ?? '',
            price: $data['price'] ?? '',
            description: $data['description'] ?? null,
            features: $data['features'] ?? null,
            highlighted: !empty($data['highlighted']),
            accentColor: $data['accent_color'] ?? null,
        );
    }
}

final readonly class PricingBlockDTO extends BlockDTO
{
    /**
     * @param PricingPlanDTO[] $plans
     */
    public function __construct(
        public string $heading,
        public ?string $intro,
        public array $plans,
    ) {}

    public static function type(): BlockType
    {
        return BlockType::Pricing;
    }

    public static function fromArray(array $data): static
    {
        $plans = array_map(
            fn(array $plan) => PricingPlanDTO::fromArray($plan),
            $data['plans'] ?? []
        );

        return new self(
            heading: $data['heading'] ?? 'Precios',
            intro: $data['intro'] ?? null,
            plans: $plans,
        );
    }

    protected function data(): array
    {
        return [
            'heading' => $this->heading,
            'intro' => $this->intro,
            'plans' => array_map(fn($plan) => [
                'name' => $plan->name,
                'price' => $plan->price,
                'description' => $plan->description,
                'features' => $plan->features,
                'highlighted' => $plan->highlighted,
                'accent_color' => $plan->accentColor,
            ], $this->plans),
        ];
    }
}