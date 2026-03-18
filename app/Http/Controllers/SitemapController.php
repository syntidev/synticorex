<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\View\View;
use Illuminate\Http\Response;
use Carbon\Carbon;

class SitemapController extends Controller
{
    public function index(): Response
    {
        $urls = [
            [
                'url' => route('home'),
                'priority' => '1.0',
                'changefreq' => 'weekly',
                'lastmod' => Carbon::now()->toAtomString(),
            ],
            [
                'url' => route('marketing.studio'),
                'priority' => '0.9',
                'changefreq' => 'monthly',
                'lastmod' => Carbon::now()->toAtomString(),
            ],
            [
                'url' => route('marketing.food'),
                'priority' => '0.9',
                'changefreq' => 'monthly',
                'lastmod' => Carbon::now()->toAtomString(),
            ],
            [
                'url' => route('marketing.cat'),
                'priority' => '0.9',
                'changefreq' => 'monthly',
                'lastmod' => Carbon::now()->toAtomString(),
            ],
            [
                'url' => route('marketing.planes'),
                'priority' => '0.9',
                'changefreq' => 'weekly',
                'lastmod' => Carbon::now()->toAtomString(),
            ],
            [
                'url' => route('marketing.about'),
                'priority' => '0.7',
                'changefreq' => 'monthly',
                'lastmod' => Carbon::now()->toAtomString(),
            ],
            [
                'url' => route('marketing.contacto'),
                'priority' => '0.7',
                'changefreq' => 'monthly',
                'lastmod' => Carbon::now()->toAtomString(),
            ],
        ];

        return response()->view('sitemap', ['urls' => $urls])
            ->header('Content-Type', 'application/xml; charset=utf-8');
    }
}
