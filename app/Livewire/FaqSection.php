<?php

declare(strict_types=1);

namespace App\Livewire;

use App\DTO\Blocks\FaqBlockDTO;
use App\Models\Page;
use Livewire\Component;

final class FaqSection extends Component
{
    public array $items = [];
    public string $heading = 'Preguntas Frecuentes';
    public ?string $intro = null;

    public function mount(string $pageSlug): void
    {
        $page = Page::where('slug', $pageSlug)->where('is_published', true)->first();

        if (! $page) {
            return;
        }

        $faqBlock = collect($page->builder_content ?? [])
            ->firstWhere('type', 'faq');

        if (! $faqBlock) {
            return;
        }

        $dto = FaqBlockDTO::fromArray($faqBlock);

        $this->heading = $dto->heading;
        $this->intro = $dto->intro;
        $this->items = array_map(fn($item) => [
            'question' => $item->question,
            'answer' => $item->answer,
        ], $dto->items);
    }

    public function render(): \Illuminate\View\View
    {
        return view('livewire.faq-section');
    }
}