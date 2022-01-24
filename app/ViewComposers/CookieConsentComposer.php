<?php

namespace App\ViewComposers;

use App\Models\Page;
use App\Models\Site;
use Illuminate\View\View;
use Illuminate\Support\Facades\Log;
use Throwable;

class CookieConsentComposer
{
    public function compose(View $view): void
    {
        $view->with(['cookiePolicyLink' => $this->getCookiePolicyPageUrl()]);
    }

    private function getCookiePolicyPageUrl(): string
    {
        $site = $this->getSite();
        $cookiePolicyPage = $this->getCookiePolicyPage($site);

        if (is_null($cookiePolicyPage)) {
            return '';
        }

        return pageLink($cookiePolicyPage);
    }

    private function getSite(): Site
    {
        try {
            $site = resolve('currentSite');
        } catch (Throwable $e) {
            Log::error($e);
            $site = new Site();
        }

        return $site;
    }

    private function getCookiePolicyPage(Site $site): ?Page
    {
        return Page::forSite($site)
            ->variety(Page::VARIETY_COOKIE_POLICY)
            ->displayable()
            ->first();
    }
}
