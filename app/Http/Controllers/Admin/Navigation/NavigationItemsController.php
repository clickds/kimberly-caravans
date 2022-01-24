<?php

namespace App\Http\Controllers\Admin\Navigation;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Navigations\NavigationItems\StoreRequest;
use App\Http\Requests\Admin\Navigations\NavigationItems\UpdateRequest;
use App\Models\Navigation;
use App\Models\NavigationItem;
use Illuminate\Http\RedirectResponse;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\View\View;

class NavigationItemsController extends Controller
{
    public function index(Navigation $navigation): View
    {
        $navigationItemsTree = !is_null(old('navigation_items'))
            ? json_decode(old('navigation_items'), true)
            : $this->buildNavigationItemsTree($navigation->navigationItems);

        return view('admin.navigation.navigation-items.index', [
            'navigation' => $navigation,
            'navigationItems' => $navigation->navigationItems()->orderBy('display_order', 'asc')->get(),
            'navigationItemsTree' => $navigationItemsTree,
        ]);
    }

    public function create(Navigation $navigation): View
    {
        $navigationItems = $navigation->navigationItems()->orderBy('name', 'asc')->get();

        return view('admin.navigation.navigation-items.create', [
            'navigation' => $navigation,
            'navigationItem' => new NavigationItem(),
            'navigationItems' => $navigationItems,
            'backgroundColours' => NavigationItem::BACKGROUND_COLOURS,
        ]);
    }

    public function store(StoreRequest $request, Navigation $navigation): RedirectResponse
    {
        $navigation->navigationItems()->create($request->validated());

        return redirect()
            ->route('admin.navigations.navigation-items.index', $navigation)
            ->with('success', 'Successfully created navigation item');
    }

    public function edit(Navigation $navigation, NavigationItem $navigationItem): View
    {
        $navigationItems = $navigation->navigationItems()
            ->where('id', '!=', $navigationItem->id)->orderBy('name', 'asc')->get();

        return view('admin.navigation.navigation-items.edit', [
            'navigation' => $navigation,
            'navigationItem' => $navigationItem,
            'navigationItems' => $navigationItems,
            'backgroundColours' => NavigationItem::BACKGROUND_COLOURS,
        ]);
    }

    public function update(
        UpdateRequest $request,
        Navigation $navigation,
        NavigationItem $navigationItem
    ): RedirectResponse {
        $navigationItem->update($request->validated());

        return redirect()
            ->route('admin.navigations.navigation-items.index', $navigation)
            ->with('success', 'Successfully updated navigation item');
    }

    public function destroy(Navigation $navigation, NavigationItem $navigationItem): RedirectResponse
    {
        $navigationItem->delete();

        return redirect()
            ->route('admin.navigations.navigation-items.index', $navigation)
            ->with('success', 'Successfully destroyed navigation item');
    }

    /**
     * @param Collection<NavigationItem> $navigationItems
     */
    public function buildNavigationItemsTree(Collection $navigationItems): array
    {
        $parents = $navigationItems
            ->where('parent_id', '=', null)
            ->sortBy('display_order');

        $children = $navigationItems
            ->where('parent_id', '!=', null)
            ->sortBy('display_order');

        $navigationItemsTree = [];

        $parents->map(function (NavigationItem $parent) use ($children, &$navigationItemsTree) {
            $branch = ['navigationItemId' => $parent->id, 'text' => $parent->name];
            $childBranches = [];

            $children
                ->where('parent_id', '=', $parent->id)
                ->map(function (NavigationItem $child) use (&$childBranches) {
                    $childBranches[] = ['navigationItemId' => $child->id, 'text' => $child->name];
                });

            $branch['children'] = $childBranches;
            $navigationItemsTree[] = $branch;
        });

        return $navigationItemsTree;
    }
}
