<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Models\Page;
use App\Models\PageView;
use App\Models\Product;
use App\Models\ReviewSubmission;
use Livewire\Component;

final class GenericPage extends Component
{
    public string $slug;
    public bool $isPreview = false;

    public function mount(string $slug): void
    {
        $this->slug = $slug;
        $this->isPreview = request()->hasValidSignature();

        $query = Page::where('slug', $slug);
        if (! $this->isPreview) {
            $query->where('is_published', true);
        }

        $page = $query->first();

        if (! $page) {
            abort(404);
        }

        if (! $this->isPreview) {
            PageView::record('pagina', $slug, $page->title);
        }
    }

    public function render(): \Illuminate\View\View
    {
        $query = Page::where('slug', $this->slug);
        if (! $this->isPreview) {
            $query->where('is_published', true);
        }
        $page = $query->first();

        return view('livewire.generic-page', [
            'page'             => $page,
            'isPreview'        => $this->isPreview,
            'siteInfo'         => siteInfo(),
            'featuredProducts' => Product::where('is_featured', true)->where('is_active', true)->with('category')->get(),
            'reviews'          => ReviewSubmission::whereIn('rating', [4, 5])->latest()->take(5)->get(),
        ])->layout('components.layouts.app', [
            'title' => ($this->isPreview ? '[PREVIEW] ' : '') . ($page?->title ?? siteName()),
        ]);
    }
}