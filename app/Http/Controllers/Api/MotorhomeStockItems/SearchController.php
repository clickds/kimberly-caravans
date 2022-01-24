<?php

namespace App\Http\Controllers\Api\MotorhomeStockItems;

use App\Http\Controllers\Traits\RetrievesFilters;
use App\Http\Controllers\Traits\RetrievesSorts;
use App\Http\Resources\Api\MotorhomeStockItemResource;
use App\Models\MotorhomeStockItem;
use App\QueryBuilders\MotorhomeStockItemQueryBuilder;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Routing\Controller;

class SearchController extends Controller
{
    use RetrievesFilters;
    use RetrievesSorts;

    private MotorhomeStockItemQueryBuilder $motorhomeStockItemBuilder;

    public function __construct()
    {
        $baseQuery = MotorhomeStockItem::eligibleForStockSearch();
        $this->motorhomeStockItemBuilder = new MotorhomeStockItemQueryBuilder($baseQuery);
    }

    public function __invoke(Request $request): AnonymousResourceCollection
    {
        $perPage = $request->input('per_page', 12);
        $order = $request->input('order', 'price_asc');
        $searchTerm = $request->input('search');

        $motorhomeStockItemsQuery = $this->motorhomeStockItemBuilder->build(
            $this->getFilters($request),
            $this->getSorts($request),
            $order,
            $searchTerm
        );

        if ($exclusionIds = $request->input('exclude_ids')) {
            $motorhomeStockItemsQuery->whereNotIn('id', $exclusionIds);
        }

        $motorhomeStockItems = $motorhomeStockItemsQuery->paginate($perPage);

        return MotorhomeStockItemResource::collection($motorhomeStockItems);
    }
}
