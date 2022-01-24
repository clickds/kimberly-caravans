<?php

namespace App\Services\Site\RedirectCalculators;

use App\Models\MotorhomeRange;
use App\Models\Page;
use App\QueryBuilders\AbstractStockItemQueryBuilder;
use Illuminate\Http\RedirectResponse;

final class MotorhomeRangePageRedirectCalculator implements RedirectCalculator
{
    private Page $page;
    private MotorhomeRange $motorhomeRange;

    public function __construct(Page $page)
    {
        $this->page = $page;
        $this->motorhomeRange = $page->pageable;
    }

    public function calculateRedirect(): ?RedirectResponse
    {
        if (false === $this->motorhomeRange->manufacturer->link_directly_to_stock_search) {
            return null;
        }

        return $this->getMotorhomeSearchPageRedirect();
    }

    private function getMotorhomeSearchPageRedirect(): ?RedirectResponse
    {
        $motorhomeSearchPage = Page::forSite($this->page->site)
            ->displayable()
            ->template(Page::TEMPLATE_MOTORHOME_SEARCH)
            ->first();

        if (is_null($motorhomeSearchPage)) {
            return null;
        }

        $redirectUrl = sprintf(
            '%s?%s=%s&%s=%s&%s=%s',
            pageLink($motorhomeSearchPage),
            AbstractStockItemQueryBuilder::FILTER_MANUFACTURER,
            $this->motorhomeRange->manufacturer->name,
            AbstractStockItemQueryBuilder::FILTER_STATUS,
            AbstractStockItemQueryBuilder::STATUS_NEW_STOCK,
            'search-term',
            $this->motorhomeRange->name
        );

        return new RedirectResponse($redirectUrl, 307);
    }
}
