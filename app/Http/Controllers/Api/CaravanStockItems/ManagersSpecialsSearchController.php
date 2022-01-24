<?php

namespace App\Http\Controllers\Api\CaravanStockItems;

use App\Http\Controllers\Traits\RetrievesFilters;
use App\Http\Controllers\Traits\RetrievesSorts;
use App\Http\Resources\Api\CaravanStockItemResource;
use App\Models\CaravanStockItem;
use App\QueryBuilders\CaravanStockItemQueryBuilder;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Log;

class ManagersSpecialsSearchController extends Controller
{
    use RetrievesFilters;
    use RetrievesSorts;

    private CaravanStockItemQueryBuilder $caravanStockItemBuilder;

    public function __construct()
    {
        $baseQuery = CaravanStockItem::live()->managersSpecial();
        $this->caravanStockItemBuilder = new CaravanStockItemQueryBuilder($baseQuery);
    }

    public function __invoke(Request $request): AnonymousResourceCollection
    {

        $perPage = $request->input('per_page', 12);
        $order = $request->input('order', 'price_asc');
        $searchTerm = $request->input('search');

        $caravanStockItemsQuery = $this->caravanStockItemBuilder->build(
            $this->getFilters($request),
            $this->getSorts($request),
            $order,
            $searchTerm
        );

        if ($exclusionIds = $request->input('exclude_ids')) {
            $caravanStockItemsQuery->whereNotIn('id', $exclusionIds);
        }

        if ($dealerId = $request->input('dealer_id')) {
            $caravanStockItemsQuery->where('dealer_id', $dealerId);
        }

        $caravanStockItems = $caravanStockItemsQuery->paginate($perPage);

        return CaravanStockItemResource::collection($caravanStockItems);
    }
}
