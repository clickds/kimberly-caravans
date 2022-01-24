<?php

namespace App\Presenters\PanelType;

use App\Models\Page;
use App\Presenters\Page\BasePagePresenter;
use App\QueryBuilders\AbstractStockItemQueryBuilder;
use Illuminate\Support\Collection;

class StockItemCategoryTabsPresenter extends BasePanelPresenter
{
    private const PAGE_TEMPLATES = [
        Page::TEMPLATE_CARAVAN_SEARCH,
        Page::TEMPLATE_MOTORHOME_SEARCH,
    ];

    private Collection $pages;

    public function newArrivalsFilter(): string
    {
        return AbstractStockItemQueryBuilder::STATUS_NEW_ARRIVALS;
    }

    public function newStockFilter(): string
    {
        return AbstractStockItemQueryBuilder::STATUS_NEW_STOCK;
    }

    public function usedStockFilter(): string
    {
        return AbstractStockItemQueryBuilder::STATUS_USED_STOCK;
    }

    public function getCaravanSearchPage(): ?BasePagePresenter
    {
        $page = $this->getPages()->first(function ($page) {
            return $page->template === Page::TEMPLATE_CARAVAN_SEARCH;
        });
        return $this->instantiatePresenter($page);
    }

    public function getMotorhomeSearchPage(): ?BasePagePresenter
    {
        $page = $this->getPages()->first(function ($page) {
            return $page->template === Page::TEMPLATE_MOTORHOME_SEARCH;
        });
        return $this->instantiatePresenter($page);
    }

    private function instantiatePresenter(?Page $page = null): ?BasePagePresenter
    {
        if (is_null($page)) {
            return null;
        }
        $presenter = new BasePagePresenter();
        ;
        $presenter->setWrappedObject($page);
        return $presenter;
    }

    private function getPages(): Collection
    {
        if (!isset($this->pages)) {
            $this->pages = $this->fetchPages();
        }
        return $this->pages;
    }

    private function fetchPages(): Collection
    {
        $site = resolve('currentSite');
        return Page::forSite($site)->displayable()
            ->whereIn('template', self::PAGE_TEMPLATES)
            ->get();
    }
}
