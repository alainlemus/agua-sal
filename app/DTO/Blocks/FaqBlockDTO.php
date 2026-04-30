<?php

declare(strict_types=1);

namespace App\DTO\Blocks;

use App\Enums\BlockType;

final readonly class FaqItemDTO
{
    public function __construct(
        public string $question,
        public string $answer,
    ) {}

    public static function fromArray(array $data): static
    {
        return new self(
            question: $data['question'] ?? '',
            answer: $data['answer'] ?? '',
        );
    }
}

final readonly class FaqBlockDTO extends BlockDTO
{
    /**
     * @param FaqItemDTO[] $items
     */
    public function __construct(
        public string $heading,
        public ?string $intro,
        public array $items,
    ) {}

    public static function type(): BlockType
    {
        return BlockType::Faq;
    }

    public static function fromArray(array $data): static
    {
        $items = array_map(
            fn(array $item) => FaqItemDTO::fromArray($item),
            $data['items'] ?? []
        );

        return new self(
            heading: $data['heading'] ?? 'Preguntas Frecuentes',
            intro: $data['intro'] ?? null,
            items: $items,
        );
    }

    protected function data(): array
    {
        return [
            'heading' => $this->heading,
            'intro' => $this->intro,
            'items' => array_map(fn($item) => [
                'question' => $item->question,
                'answer' => $item->answer,
            ], $this->items),
        ];
    }
}