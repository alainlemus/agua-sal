<?php

declare(strict_types=1);

namespace App\Http\Controllers\SEO;

use App\Http\Controllers\Controller;
use Illuminate\Http\Response;

final class RobotsController extends Controller
{
    public function __invoke(): Response
    {
        $siteInfo = \App\Models\SiteInfo::first();
        $sitemapUrl = url('/sitemap.xml');

        $robots = <<<ROBOTS
User-agent: *
Allow: /

Disallow: /admin/
Disallow: /api/
Disallow: /preview/
Disallow: /resena/
Disallow: /qr/
Disallow: /?_debugbar=
Disallow: /?_pjax=

Sitemap: {$sitemapUrl}

ROBOTS;

        return response($robots, 200, [
            'Content-Type' => 'text/plain; charset=utf-8',
            'X-Robots-Tag' => 'noindex',
        ]);
    }
}