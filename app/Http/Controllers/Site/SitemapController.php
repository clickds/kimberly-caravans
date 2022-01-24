<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function index(): Response
    {
        $site = resolve('currentSite');
        $pages = $site->pages()->displayable()
            ->with('parent:id,slug')->select('id', 'parent_id', 'slug')->get();
        return response()->view('site.sitemap.index', [
            'pages' => $pages,
        ])->header('Content-Type', 'text/xml');
    }
}
