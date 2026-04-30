<?php

namespace App\Livewire;

use App\Models\SiteInfo;
use Livewire\Component;

class PrivacyPage extends Component
{
    public function render()
    {
        $siteInfo = SiteInfo::first();

        return view('livewire.privacy-page', compact('siteInfo'))
            ->layout('components.layouts.app', [
                'title' => $siteInfo?->privacy_policy_title ?: 'Aviso de Privacidad',
            ]);
    }
}
