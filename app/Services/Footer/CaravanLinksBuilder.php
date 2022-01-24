<?php

namespace App\Services\Footer;

use App\Models\FooterLink;
use App\Models\CaravanStockItem;
use App\Models\Page;
use App\Models\Site;
use App\Presenters\Page\BasePagePresenter;
use App\QueryBuilders\CaravanStockItemQueryBuilder;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Support\Collection;

class CaravanLinksBuilder extends BaseLinksBuilder
{
    public const STATUSES = [
        CaravanStockItemQueryBuilder::STATUS_NEW_STOCK,
        CaravanStockItemQueryBuilder::STATUS_USED_STOCK,
    ];

    public function __construct(Site $site)
    {
        $pages = $this->fetchPages($site);
        $this->stockSearchPage = $pages->first(function ($page) {
            return $page->template === Page::TEMPLATE_CARAVAN_SEARCH;
        });
        $this->newModelsPage = $pages->first(function ($page) {
            return $page->template === Page::TEMPLATE_NEW_CARAVANS;
        });
    }

    public function call(): Collection
    {
        $links = $this->stockSearchLinks();
        $links = $links->merge($this->newCaravanPageLinks());

        return $links;
    }

    private function newCaravanPageLinks(): array
    {
        $items = [];
        $page = $this->getNewModelsPage();
        if (is_null($page)) {
            return $items;
        }
        $presenter = $this->buildPresenter($page);
        $items[] = new FooterLink('New Caravans', $presenter->link());

        return $items;
    }

    private function stockSearchLinks(): Collection
    {
        $links = new Collection();
        $page = $this->getStockSearchPage();
        if (is_null($page)) {
            return $links;
        }

        $presenter = $this->buildPresenter($page);

        foreach ($this->fetchAxles() as $axle) {
            $link = $this->buildAxleLink($presenter, $axle);
            $links->push($link);
        }

        return $links;
    }

    private function buildAxleLink(BasePagePresenter $presenter, string $axle): FooterLink
    {
        return new FooterLink($axle . ' Axle', $presenter->link(['axles' => $axle]));
    }

    private function fetchAxles(): Collection
    {
        return CaravanStockItem::toBase()->select('axles')->distinct()->pluck('axles');
    }

    /**
     * @return EloquentCollection<\App\Models\Page>
     */
    private function fetchPages(Site $site): EloquentCollection
    {
        return $site->pages()->displayable()
            ->with('parent:id,slug')
            ->whereIn('template', [
                Page::TEMPLATE_CARAVAN_SEARCH,
                Page::TEMPLATE_NEW_CARAVANS,
            ])
            ->select('parent_id', 'slug', 'template')
            ->get();
    }
}
