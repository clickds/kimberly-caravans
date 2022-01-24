<?php

namespace App\Http\Controllers\Api\CaravanStockItems;

use App\Http\Controllers\Traits\RetrievesFilters;
use App\Http\Controllers\Traits\RetrievesSorts;
use App\Http\Resources\Api\CaravanStockItemResource;
use App\Models\CaravanStockItem;
use App\Models\SpecialOffer;
use App\QueryBuilders\CaravanStockItemQueryBuilder;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Routing\Controller;

class SpecialOfferSearchController extends Controller
{
    use RetrievesFilters;
    use RetrievesSorts;

    public function __invoke(Request $request, SpecialOffer $specialOffer): AnonymousResourceCollection
    {
        $baseQuery = $specialOffer->caravanStockItems()->live()->getQuery();
        $caravanStockItemBuilder = new CaravanStockItemQueryBuilder($baseQuery);

        $perPage = $request->input('per_page', 12);
        $order = $request->input('order', 'price_asc');
        $searchTerm = $request->input('search');

        $caravanStockItemsQuery = $caravanStockItemBuilder->build(
            $this->getFilters($request),
            $this->getSorts($request),
            $order,
            $searchTerm
        );

        if ($exclusionIds = $request->input('exclude_ids')) {
            $caravanStockItemsQuery->whereNotIn('id', $exclusionIds);
        }

        $caravanStockItems = $caravanStockItemsQuery->paginate($perPage);

        return CaravanStockItemResource::collection($caravanStockItems);
    }
}
