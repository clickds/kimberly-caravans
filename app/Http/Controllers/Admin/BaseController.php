<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use App\Models\Site;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

abstract class BaseController extends Controller
{
    protected function redirectUrl(Request $request): ?string
    {
        return $request->input('redirect_url');
    }

    protected function fetchSites(array $ignoreIds = []): Collection
    {
        return Site::whereNotIn('id', $ignoreIds)->orderBy('country', 'asc')
            ->select('country', 'id')->get();
    }

    protected function getPagesWithTemplate(string $templateName, ?Model $pageable = null): Collection
    {
        $query = Page::displayable()->template($templateName);

        if (is_null($pageable)) {
            return $query->get();
        }

        return $query->where('pageable_type', get_class($pageable))
            ->where('pageable_id', $pageable->id)
            ->get();
    }
}
