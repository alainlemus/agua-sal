<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Models\Menu;
use App\Models\PageView;
use Livewire\Component;

final class MenuPage extends Component
{
    public string $activeTab = '';

    public function mount(): void
    {
        $tab = request()->query('tab', '');
        $menus = Menu::active()->get();

        if ($menus->isEmpty()) {
            return;
        }

        $this->activeTab = $menus->firstWhere('slug', $tab)?->slug ?? $menus->first()->slug;

        PageView::record('menu', 'menu:' . $this->activeTab, 'Menú: ' . ($menus->firstWhere('slug', $this->activeTab)?->name ?? $this->activeTab));
    }

    public function setTab(string $slug): void
    {
        $this->activeTab = $slug;

        $menu = Menu::active()->where('slug', $slug)->first();
        PageView::record('menu', 'menu:' . $slug, 'Menú: ' . ($menu?->name ?? $slug));
    }

    public function render(): \Illuminate\View\View
    {
        $menus = Menu::active()
            ->with(['sections' => fn ($q) => $q->with('category')])
            ->get();

        return view('livewire.menu-page', [
            'menus' => $menus,
        ])->layout('components.layouts.app', ['title' => 'Menú']);
    }
}