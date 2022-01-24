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

class CaravanStockItemController extends Controller
{
    use RetrievesFilters;
    use RetrievesSorts;

    /**
     * @var CaravanStockItemQueryBuilder
     */
    private $caravanStockItemBuilder;

    public function __construct(CaravanStockItemQueryBuilder $caravanStockItemBuilder)
    {
        $this->caravanStockItemBuilder = $caravanStockItemBuilder;
    }

    public function index(Request $request): AnonymousResourceCollection
    {
        $ids = $request->get('ids', []);

        if (!empty($ids)) {
            $caravanStockItems = CaravanStockItem::whereIn('id', $ids)->get();
        } else {
            $caravanStockItems = CaravanStockItem::all();
        }

        return CaravanStockItemResource::collection($caravanStockItems);
    }
}
