<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\Admin\PageResource;
use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class PagesController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $query = Page::query();
        if ($request->has('ids')) {
            $ids = $request->input('ids');
            $query = $query->whereIn('id', $ids);
        }
        $pages = $query->orderBy('name', 'asc')->get();

        return PageResource::collection($pages);
    }

    public function show(Page $page): PageResource
    {
        return new PageResource($page);
    }
}
