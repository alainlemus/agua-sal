<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Models\PageView;
use App\Models\Product;
use App\Models\ReviewSubmission;
use Livewire\Component;

final class HomePage extends Component
{
    public function mount(): void
    {
        PageView::record('home', 'home', 'Inicio');
    }

    public function render(): \Illuminate\View\View
    {
        $page = \App\Models\Page::where('slug', 'home')->where('is_published', true)->first();

        $featuredBlock = collect($page?->builder_content ?? [])
            ->firstWhere('type', 'featured_products');

        $selectedIds = $featuredBlock['data']['product_ids'] ?? [];

        $featuredProducts = $this->getFeaturedProducts($selectedIds);

        return view('livewire.home-page', [
            'page'             => $page,
            'featuredProducts' => $featuredProducts,
            'siteInfo'         => siteInfo(),
            'reviews'          => ReviewSubmission::whereIn('rating', [4, 5])->latest()->take(5)->get(),
        ]);
    }

    /**
     * @param array<int> $selectedIds
     * @return \Illuminate\Database\Eloquent\Collection<Product>
     */
    private function getFeaturedProducts(array $selectedIds): \Illuminate\Database\Eloquent\Collection
    {
        if (! empty($selectedIds)) {
            $selectedIds = array_map('intval', $selectedIds);
            return Product::whereIn('id', $selectedIds)
                ->where('is_active', true)
                ->with('category')
                ->get()
                ->sortBy(fn (Product $p) => array_search($p->id, $selectedIds))
                ->values();
        }

        return Product::where('is_featured', true)
            ->where('is_active', true)
            ->with('category')
            ->get();
    }
}