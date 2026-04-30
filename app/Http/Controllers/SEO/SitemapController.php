<?php

declare(strict_types=1);

namespace App\Http\Controllers\SEO;

use App\Http\Controllers\Controller;
use App\Models\Page;
use App\Models\SiteInfo;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;

final class SitemapController extends Controller
{
    public function __invoke(): Response
    {
        $sitemap = Cache::remember('sitemap.xml', 3600, function (): string {
            $siteInfo = SiteInfo::first();
            $pages = Page::where('is_published', true)->get(['slug', 'updated_at']);

            $urls = [];

            // Homepage
            $urls[] = [
                'loc' => url('/'),
                'lastmod' => now()->toDateString(),
                'changefreq' => 'daily',
                'priority' => '1.0',
            ];

            // Static routes
            $staticRoutes = ['/menu', '/aviso-de-privacidad'];
            foreach ($staticRoutes as $route) {
                $urls[] = [
                    'loc' => url($route),
                    'lastmod' => now()->toDateString(),
                    'changefreq' => 'weekly',
                    'priority' => $route === '/menu' ? '0.9' : '0.5',
                ];
            }

            // Dynamic CMS pages
            foreach ($pages as $page) {
                if ($page->slug === 'home') {
                    continue;
                }
                $urls[] = [
                    'loc' => url('/' . $page->slug),
                    'lastmod' => $page->updated_at?->toDateString() ?? now()->toDateString(),
                    'changefreq' => 'monthly',
                    'priority' => '0.7',
                ];
            }

            return $this->generateXml($urls);
        });

        return response($sitemap, 200, [
            'Content-Type' => 'application/xml; charset=utf-8',
            'X-Robots-Tag' => 'noindex',
        ]);
    }

    private function generateXml(array $urls): string
    {
        $xml = '<?xml version="1.0" encoding="UTF-8"?>';
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';

        foreach ($urls as $url) {
            $xml .= '<url>';
            $xml .= '<loc>' . htmlspecialchars($url['loc'], ENT_XML1) . '</loc>';
            $xml .= '<lastmod>' . $url['lastmod'] . '</lastmod>';
            $xml .= '<changefreq>' . $url['changefreq'] . '</changefreq>';
            $xml .= '<priority>' . $url['priority'] . '</priority>';
            $xml .= '</url>';
        }

        $xml .= '</urlset>';

        return $xml;
    }
}