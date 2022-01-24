<?php

namespace App\Presenters;

use App\Models\Page;
use McCool\LaravelAutoPresenter\BasePresenter;

class VacancyPresenter extends BasePresenter
{
    public function getApplicationSubmissionUrl(): string
    {
        return route('vacancy-applications', ['vacancy' => $this->getWrappedObject()->id]);
    }

    public function getPostedDate(): string
    {
        return $this->getWrappedObject()->published_at->format('jS F Y');
    }

    public function getClosingDate(): string
    {
        $closingDate = $this->getWrappedObject()->expired_at;

        return is_null($closingDate) ? 'On Going' : $closingDate->format('jS F Y');
    }

    public function getDealerNames(): array
    {
        return $this->getWrappedObject()->dealers->pluck('name')->toArray();
    }

    public function getDetailPageUrl(): string
    {
        $currentSite = resolve('currentSite');

        $sitePage = $this->getWrappedObject()->sitePage($currentSite);

        if (is_null($sitePage) || !is_a($sitePage, Page::class)) {
            return '';
        }

        return pageLink($sitePage);
    }

    public function getCurrencySymbol(): string
    {
        $currentSite = resolve('currentSite');

        $sitePresenter = (new SitePresenter())->setWrappedObject($currentSite);

        return $sitePresenter->currencySymbol();
    }
}
