<?php

namespace App\Presenters\Page;

use App\Models\Page;
use App\Models\VideoBanner;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Request;
use McCool\LaravelAutoPresenter\BasePresenter;

class BasePagePresenter extends BasePresenter
{
    public const TEMPLATES_WITH_BANNERS = [
        Page::TEMPLATE_DEALERS_LISTING,
        Page::TEMPLATE_DEALER_SHOW,
        Page::TEMPLATE_HOMEPAGE,
        Page::TEMPLATE_STANDARD,
        Page::TEMPLATE_TESTIMONIALS_LISTING,
    ];

    public function siteLink(array $params = []): string
    {
        $page = $this->getWrappedObject();
        $path = $this->link($params, false);
        $host = Request::getHttpHost();

        if (App::environment(['production'])) {
            $parts = explode('.', $host);
            if ($page->site) {
                $parts[0] = $page->site->subdomain;
            }
            $host = implode('.', $parts);
        }

        return '//' . $host . $path;
    }

    public function link(array $params = [], bool $absoluteUrl = true): string
    {
        $page = $this->getWrappedObject();
        if (is_null($page->parent)) {
            $params = array_merge($params, [
                'page' => $page->slug,
            ]);
            return route('site', $params, $absoluteUrl);
        }

        $params = array_merge($params, [
            'page' => $page->parent->slug,
            'childPage' => $page->slug,
        ]);
        return route('site', $params, $absoluteUrl);
    }

    public function getVideoBanner(): ?VideoBanner
    {
        return $this->getWrappedObject()->videoBanner;
    }

    public function getImageBanners(): Collection
    {
        return $this->getWrappedObject()->imageBanners;
    }
}
