<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Page;
use App\Models\SiteInfo;

class BbqPage extends Component
{
    public function render()
    {
        // Intentamos múltiples slugs posibles según lo que el admin haya escrito
        $page = Page::whereIn('slug', ['bbq', 'ahumados', 'ahumados-bbq', 'nuestros-ahumados'])
                    ->where('is_published', true)
                    ->first();

        return view('livewire.generic-page', [
            'page'     => $page,
            'siteInfo' => SiteInfo::first(),
        ])->layout('components.layouts.app', ['title' => $page?->title ?? 'Ahumados BBQ']);
    }
}
