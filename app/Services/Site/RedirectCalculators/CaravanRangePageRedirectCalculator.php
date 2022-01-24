<?php

namespace App\Services\Site\RedirectCalculators;

use App\Models\CaravanRange;
use App\Models\Page;
use App\QueryBuilders\AbstractStockItemQueryBuilder;
use Illuminate\Http\RedirectResponse;

final class CaravanRangePageRedirectCalculator implements RedirectCalculator
{
    private Page $page;
    private CaravanRange $caravanRange;

    public function __construct(Page $page)
    {
        $this->page = $page;
        $this->caravanRange = $page->pageable;
    }

    public function calculateRedirect(): ?RedirectResponse
    {
        if (false === $this->caravanRange->manufacturer->link_directly_to_stock_search) {
            return null;
        }

        return $this->getCaravanSearchPageRedirect();
    }

    private function getCaravanSearchPageRedirect(): ?RedirectResponse
    {
        $caravanSearchPage = Page::forSite($this->page->site)
            ->displayable()
            ->template(Page::TEMPLATE_CARAVAN_SEARCH)
            ->first();

        if (is_null($caravanSearchPage)) {
            return null;
        }

        $redirectUrl = sprintf(
            '%s?%s=%s&%s=%s&%s=%s',
            pageLink($caravanSearchPage),
            AbstractStockItemQueryBuilder::FILTER_MANUFACTURER,
            $this->caravanRange->manufacturer->name,
            AbstractStockItemQueryBuilder::FILTER_STATUS,
            AbstractStockItemQueryBuilder::STATUS_NEW_STOCK,
            'search-term',
            $this->caravanRange->name
        );

        return new RedirectResponse($redirectUrl, 307);
    }
}
