<?php

namespace App\Filament\Widgets;

use App\Models\ContactSubmission;
use Filament\Widgets\Widget;

class PendingMessagesWidget extends Widget
{
    protected static string $view = 'filament.widgets.pending-messages-widget';

    protected static ?int $sort = 1;

    protected int | string | array $columnSpan = 'full';

    protected static bool $isLazy = false;

    public function getUnattendedCount(): int
    {
        return ContactSubmission::unattended()->count();
    }

    public function getLatestMessages(): \Illuminate\Database\Eloquent\Collection
    {
        return ContactSubmission::unattended()
            ->latest()
            ->limit(5)
            ->get();
    }
}
