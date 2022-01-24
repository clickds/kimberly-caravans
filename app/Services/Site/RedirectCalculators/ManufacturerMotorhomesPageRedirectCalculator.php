<?php

namespace App\Services\Site\RedirectCalculators;

use App\Models\Manufacturer;
use App\Models\Page;
use App\QueryBuilders\AbstractStockItemQueryBuilder;
use Illuminate\Http\RedirectResponse;

final class ManufacturerMotorhomesPageRedirectCalculator implements RedirectCalculator
{
    private Page $page;
    private Manufacturer $manufacturer;

    public function __construct(Page $page)
    {
        $this->page = $page;
        $this->manufacturer = $page->pageable;
    }

    public function calculateRedirect(): ?RedirectResponse
    {
        if (false === $this->manufacturer->link_directly_to_stock_search) {
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
            '%s?%s=%s&%s=%s',
            pageLink($motorhomeSearchPage),
            AbstractStockItemQueryBuilder::FILTER_MANUFACTURER,
            $this->manufacturer->name,
            AbstractStockItemQueryBuilder::FILTER_STATUS,
            AbstractStockItemQueryBuilder::STATUS_NEW_STOCK
        );

        return new RedirectResponse($redirectUrl, 307);
    }
}
