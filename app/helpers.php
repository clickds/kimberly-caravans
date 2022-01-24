<?php

use App\Models\Area;
use App\Models\Page;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\App;

function pageLink(?Page $page, bool $absoluteUrl = true): string
{
    if (is_null($page)) {
        return "";
    }

    if (is_null($page->parent)) {
        return route('site', [
            'page' => $page->slug,
        ], $absoluteUrl);
    }

    return route('site', [
        'page' => $page->parent->slug,
        'childPage' => $page->slug,
    ], $absoluteUrl);
}

function sitePageLink(Page $page): string
{
    $path = pageLink($page, false);
    $host = Request::getHttpHost();

    if (App::environment(['production'])) {
        $parts = explode('.', $host);
        if ($page->site) {
            $parts[0] = $page->site->subdomain;
        }
        $host = implode('.', $parts);
    }

    return $host . $path;
}

/**
 * @param string $routeName
 * @param mixed $parameters
 * @return string
 */
function routeWithCurrentUrlAsRedirect(string $routeName, $parameters = []): string
{
    return sprintf('%s?redirect_url=%s', route($routeName, $parameters), urlencode(request()->fullUrl()));
}
