<?php

namespace App\Services\Footer;

use App\Models\FooterLink;
use App\Models\MotorhomeStockItem;
use App\Models\Page;
use App\Models\Site;
use App\Presenters\Page\BasePagePresenter;
use App\QueryBuilders\MotorhomeStockItemQueryBuilder;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Support\Collection;

class MotorhomeLinksBuilder extends BaseLinksBuilder
{
    public const STATUSES = [
        MotorhomeStockItemQueryBuilder::STATUS_NEW_STOCK,
        MotorhomeStockItemQueryBuilder::STATUS_USED_STOCK,
    ];

    public function __construct(Site $site)
    {
        $pages = $this->fetchPages($site);
        $this->stockSearchPage = $pages->first(function ($page) {
            return $page->template === Page::TEMPLATE_MOTORHOME_SEARCH;
        });
        $this->newModelsPage = $pages->first(function ($page) {
            return $page->template === Page::TEMPLATE_NEW_MOTORHOMES;
        });
    }

    public function call(): Collection
    {
        $links = $this->stockSearchLinks();
        $links = $links->merge($this->newMotorhomesPageLinks());

        return $links;
    }

    private function newMotorhomesPageLinks(): array
    {
        $items = [];
        $page = $this->getNewModelsPage();
        if (is_null($page)) {
            return $items;
        }
        $presenter = $this->buildPresenter($page);
        $items[] = new FooterLink('New Motorhomes', $presenter->link());

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

        foreach ($this->fetchConversions() as $conversion) {
            $link = $this->buildConversionLink($presenter, $conversion);
            $links->push($link);
        }

        return $links;
    }

    private function buildConversionLink(BasePagePresenter $presenter, string $conversion): FooterLink
    {
        return new FooterLink($conversion, $presenter->link(['conversion' => $conversion]));
    }

    private function fetchConversions(): Collection
    {
        return MotorhomeStockItem::toBase()->select('conversion')->distinct()->pluck('conversion');
    }

    /**
     * @return EloquentCollection<\App\Models\Page>
     */
    private function fetchPages(Site $site): EloquentCollection
    {
        return $site->pages()->displayable()
            ->with('parent:id,slug')
            ->whereIn('template', [
                Page::TEMPLATE_MOTORHOME_SEARCH,
                Page::TEMPLATE_NEW_MOTORHOMES,
            ])
            ->select('parent_id', 'slug', 'template')
            ->get();
    }
}
