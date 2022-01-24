<?php

namespace App\QueryBuilders;

use App\Models\MotorhomeStockItem;
use App\Models\Page;
use Illuminate\Database\Eloquent\Builder;

class MotorhomeStockItemQueryBuilder extends AbstractStockItemQueryBuilder
{
    private Builder $query;

    public function __construct(Builder $query = null)
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
            $motorhomeStockItemIds = $this->getMotorhomeStockItemIdsForSearchTerm($searchTerm);

            $query->whereIn('id', $motorhomeStockItemIds);
        }

        $query->with([
            'berths' => function ($query) {
                $query->orderBy('number', 'asc');
            },
            'seats' => function ($query) {
                $query->orderBy('number', 'asc');
            },
            'manufacturer' => function ($query) {
                $query->select('id', 'name');
            },
            'specialOffers' => function ($query) {
                $query->forSite($this->getSite())->forMotorhomes()->displayable()->orderedByPosition()
                    ->with([
                        'pages' => function ($query) {
                            $query->with('parent')
                                ->template(Page::TEMPLATE_SPECIAL_OFFER_MOTORHOME_SHOW)
                                ->displayable()->where('site_id', $this->getSite()->id);
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

        $query->whereHas('motorhomeRange', function ($motorhomeRangeQuery) use ($ranges) {
            $motorhomeRangeQuery->whereIn('name', $ranges);
        });
    }

    protected function specialOfferStockItemIds(array $specialOfferIds): array
    {
        return MotorhomeStockItem::join(
            'motorhome_stock_item_special_offer',
            'motorhome_stock_items.id',
            '=',
            'motorhome_stock_item_special_offer.motorhome_stock_item_id'
        )->whereIn('special_offer_id', $specialOfferIds)
            ->distinct()->toBase()->pluck('id')->toArray();
    }

    private function getMotorhomeStockItemIdsForSearchTerm(string $searchTerm): array
    {
        return MotorhomeStockItem::search($searchTerm)
            ->get()
            ->pluck('id')
            ->toArray();
    }
}
