<?php

namespace App\Http\Controllers\Api\Admin\FeedStockItems;

use App\Http\Controllers\Controller;
use App\Models\MotorhomeStockItem;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

class MotorhomeSearchController extends Controller
{
    public function __invoke(Request $request): Collection
    {
        $keywords = $request->input('keywords');
        $excludeIds = $request->input('exclude_ids', []);
        $stockItems = $this->fetchItems($keywords, $excludeIds);

        return $stockItems;
    }

    private function fetchItems(string $keywords, array $excludeIds): Collection
    {
        $keywords = '%' . $keywords . '%';
        return MotorhomeStockItem::fromFeed()->live()->where(function ($query) use ($keywords) {
            $query->where('model', 'LIKE', $keywords)
                ->orWhere('unique_code', 'LIKE', $keywords);
        })
            ->whereNotIn('id', $excludeIds)
            ->select('id', 'model', 'unique_code')->get();
    }
}
