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

class MotorhomeStockItemController extends Controller
{
    use RetrievesFilters;
    use RetrievesSorts;

    /**
     * @var MotorhomeStockItemQueryBuilder
     */
    private $motorhomeStockItemBuilder;

    public function __construct(MotorhomeStockItemQueryBuilder $motorhomeStockItemBuilder)
    {
        $this->motorhomeStockItemBuilder = $motorhomeStockItemBuilder;
    }

    public function index(Request $request): AnonymousResourceCollection
    {
        $ids = $request->get('ids', []);

        if (!empty($ids)) {
            $motorhomeStockItems = MotorhomeStockItem::whereIn('id', $ids)->get();
        } else {
            $motorhomeStockItems = MotorhomeStockItem::all();
        }

        return MotorhomeStockItemResource::collection($motorhomeStockItems);
    }
}
