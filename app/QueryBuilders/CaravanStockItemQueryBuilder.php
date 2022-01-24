<?php

namespace App\QueryBuilders;

use App\Models\CaravanStockItem;
use App\Models\Page;
use Illuminate\Database\Eloquent\Builder;

class CaravanStockItemQueryBuilder extends AbstractStockItemQueryBuilder
{
    private Builder $query;

    public function __construct(Builder $query)
    {
        $this->query = $query;
    }

    public function build(
        array $filters = [],
        array $sorts = [],
        string $order = null,
        string $searchTerm = null
    ): Builder {
        $query = $this->query;

        if (!is_null($searchTerm)) {
            $caravanStockItemIds = $this->getCaravanStockItemIdsForSearchTerm($searchTerm);

            $query->whereIn('id', $caravanStockItemIds);
        }

        $query->with([
            'berths' => function ($query) {
                $query->orderBy('number', 'asc');
            },
            'manufacturer' => function ($query) {
                $query->select('id', 'name');
            },
            'specialOffers' => function ($query) {
                $query->forSite($this->getSite())->forCaravans()->displayable()->orderedByPosition()
                    ->with([
                        'pages' => function ($query) {
                            $query->with('parent')
                                ->template(Page::TEMPLATE_SPECIAL_OFFER_CARAVAN_SHOW)
                                ->displayable()->displayable()->where('site_id', $this->getSite()->id);
                        }
                    ])->get();
            },
            'features',
        ]);

        $this->sanitiseAndApplyFilters($query, $filters);

        $this->sanitiseAndApplySorts($query, $sorts);

        $this->applyStatusQuery($filters, $query);

        $this->applyManufacturerQuery($filters, $query);

        $this->applyRangeQuery($filters, $query);

        $this->applyLayoutQuery($filters, $query);

        $this->applyOrder($order, $query);

        return $query;
    }

    protected function applyRangeQuery(array $filters, Builder $query): void
    {
        $ranges = $filters[self::FILTER_RANGE][self::FILTER_IN_KEY] ?? null;

        if (null === $ranges) {
            return;
        }

        $query->whereHas('caravanRange', function ($caravanRangeQuery) use ($ranges) {
            $caravanRangeQuery->whereIn('name', $ranges);
        });
    }

    protected function specialOfferStockItemIds(array $specialOfferIds): array
    {
        return CaravanStockItem::join(
            'caravan_stock_item_special_offer',
            'caravan_stock_items.id',
            '=',
            'caravan_stock_item_special_offer.caravan_stock_item_id'
        )->whereIn('special_offer_id', $specialOfferIds)
            ->distinct()->toBase()->pluck('id')->toArray();
    }

    private function getCaravanStockItemIdsForSearchTerm(string $searchTerm): array
    {
        return CaravanStockItem::search($searchTerm)
            ->get()
            ->pluck('id')
            ->toArray();
    }
}
