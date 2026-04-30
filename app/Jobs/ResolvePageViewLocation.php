<?php

namespace App\Jobs;

use App\Models\PageView;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Http;

class ResolvePageViewLocation implements ShouldQueue
{
    use Queueable;

    public int $tries = 2;
    public int $timeout = 10;

    public function __construct(public int $pageViewId, public string $ip) {}

    public function handle(): void
    {
        // IPs locales / privadas — no consultamos
        if ($this->isPrivateIp($this->ip)) {
            return;
        }

        $response = Http::timeout(8)->get("http://ip-api.com/json/{$this->ip}", [
            'fields' => 'status,country,countryCode,city',
            'lang'   => 'es',
        ]);

        if (! $response->ok()) {
            return;
        }

        $data = $response->json();

        if (($data['status'] ?? '') !== 'success') {
            return;
        }

        PageView::where('id', $this->pageViewId)->update([
            'country'      => $data['country']     ?? null,
            'country_code' => $data['countryCode'] ?? null,
            'city'         => $data['city']        ?? null,
        ]);
    }

    private function isPrivateIp(string $ip): bool
    {
        return filter_var(
            $ip,
            FILTER_VALIDATE_IP,
            FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE
        ) === false;
    }
}
