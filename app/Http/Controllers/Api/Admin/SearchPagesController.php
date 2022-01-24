<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\Admin\PageResource;
use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class SearchPagesController extends Controller
{
    public function __invoke(Request $request): AnonymousResourceCollection
    {
        $searchTerm = '%' . $request->get('search_term') . '%';
        $siteId = $request->get('site_id');

        $query = Page::with('site')->where('name', 'like', $searchTerm);
        if ($siteId) {
            $query = $query->where('site_id', $siteId);
        }

        $pages = $query->get();

        return PageResource::collection($pages);
    }
}
