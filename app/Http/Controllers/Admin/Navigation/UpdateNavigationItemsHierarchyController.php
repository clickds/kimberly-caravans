<?php

namespace App\Http\Controllers\Admin\Navigation;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Navigations\NavigationItems\Hierarchy\UpdateRequest;
use App\Models\Navigation;
use App\Services\Navigation\NavigationItemsHierarchyUpdater;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

class UpdateNavigationItemsHierarchyController extends Controller
{
    public function __invoke(Navigation $navigation, UpdateRequest $request): RedirectResponse
    {
        $hierarchyData = $request->validated()['navigation_items'];

        try {
            DB::beginTransaction();

            $hierarchyUpdater = new NavigationItemsHierarchyUpdater($navigation->navigationItems, $hierarchyData);

            $hierarchyUpdater->update();

            DB::commit();

            return redirect()
                ->route('admin.navigations.navigation-items.index', ['navigation' => $navigation])
                ->with('success', 'Successfully reordered navigation items');
        } catch (Throwable $e) {
            Log::error($e);

            DB::rollBack();

            return redirect()
                ->route('admin.navigations.navigation-items.index', ['navigation' => $navigation])
                ->with('error', 'Failed to reorder navigation items');
        }
    }
}
