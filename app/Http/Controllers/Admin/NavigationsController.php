<?php

namespace App\Http\Controllers\Admin;

use App\Models\Navigation;
use App\Http\Requests\Admin\Navigations\StoreRequest;
use App\Http\Requests\Admin\Navigations\UpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Throwable;

class NavigationsController extends BaseController
{
    public function index(Request $request): View
    {
        $navigations = Navigation::withCount('navigationItems')
            ->ransack($request->all())->get();

        return view('admin.navigations.index', [
            'navigations' => $navigations,
            'types' => Navigation::NAVIGATION_TYPES,
            'sites' => $this->fetchSites(),
        ]);
    }

    public function create(Request $request): View
    {
        return view('admin.navigations.create', [
            'navigation' => new Navigation(),
            'sites' => $this->fetchSites(),
            'types' => Navigation::NAVIGATION_TYPES,
            'redirectUrl' => $this->redirectUrl($request),
        ]);
    }

    public function store(StoreRequest $request): RedirectResponse
    {
        try {
            DB::beginTransaction();

            Navigation::create($request->validated());

            DB::commit();
        } catch (Throwable $e) {
            DB::rollBack();

            return back()->with('error', 'Failed to create navigation');
        }

        if ($redirect_url = $this->redirectUrl($request)) {
            return redirect($redirect_url)->with('success', 'Navigation created');
        }

        return redirect()
            ->route('admin.navigations.index')
            ->with('success', 'Navigation created');
    }

    public function edit(Navigation $navigation, Request $request): View
    {
        return view('admin.navigations.edit', [
            'navigation' => $navigation,
            'sites' => $this->fetchSites(),
            'types' => Navigation::NAVIGATION_TYPES,
            'redirectUrl' => $this->redirectUrl($request),
        ]);
    }

    public function update(UpdateRequest $request, Navigation $navigation): RedirectResponse
    {
        try {
            DB::beginTransaction();

            $navigation->update($request->validated());

            DB::commit();
        } catch (Throwable $e) {
            DB::rollBack();

            return back()->with('error', 'Failed to update navigation');
        }

        if ($redirect_url = $this->redirectUrl($request)) {
            return redirect($redirect_url)->with('success', 'Navigation updated');
        }

        return redirect()->route('admin.navigations.index')->with('success', 'Navigation updated');
    }

    public function destroy(Navigation $navigation, Request $request): RedirectResponse
    {
        $navigation->delete();

        if ($redirect_url = $this->redirectUrl($request)) {
            return redirect($redirect_url)->with('success', 'Navigation deleted');
        }

        return redirect()
            ->route('admin.navigations.index')
            ->with('warning', 'Navigation deleted');
    }
}
