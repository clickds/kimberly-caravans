<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\UsefulLinkCategories\StoreRequest;
use App\Http\Requests\Admin\UsefulLinkCategories\UpdateRequest;
use App\Models\UsefulLinkCategory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UsefulLinkCategoriesController extends BaseController
{
    public function index(): View
    {
        $usefulLinkCategories = UsefulLinkCategory::orderBy('position', 'asc')->get();

        return view('admin.useful-link-categories.index', [
            'usefulLinkCategories' => $usefulLinkCategories,
        ]);
    }

    public function create(Request $request): View
    {
        $usefulLinkCategory = new UsefulLinkCategory();

        return view('admin.useful-link-categories.create', [
            'usefulLinkCategory' => $usefulLinkCategory,
            'redirectUrl' => $this->redirectUrl($request),
        ]);
    }

    public function store(StoreRequest $request): RedirectResponse
    {
        $data = $request->validated();

        UsefulLinkCategory::create($data);

        if ($redirect_url = $this->redirectUrl($request)) {
            return redirect($redirect_url)->with('success', 'Useful link category created');
        }

        return redirect()
            ->route('admin.useful-link-categories.index')
            ->with('success', 'Useful link category created');
    }

    public function edit(UsefulLinkCategory $usefulLinkCategory, Request $request): View
    {
        return view('admin.useful-link-categories.edit', [
            'usefulLinkCategory' => $usefulLinkCategory,
            'redirectUrl' => $this->redirectUrl($request),
        ]);
    }


    public function update(UpdateRequest $request, UsefulLinkCategory $usefulLinkCategory): RedirectResponse
    {
        $data = $request->validated();

        $usefulLinkCategory->update($data);

        if ($redirect_url = $this->redirectUrl($request)) {
            return redirect($redirect_url)->with('success', 'Useful link category updated');
        }

        return redirect()
            ->route('admin.useful-link-categories.index')
            ->with('success', 'Useful link category updated');
    }

    public function destroy(UsefulLinkCategory $usefulLinkCategory, Request $request): RedirectResponse
    {
        $usefulLinkCategory->delete();

        if ($redirect_url = $this->redirectUrl($request)) {
            return redirect($redirect_url)->with('success', 'Useful link category deleted');
        }

        return redirect()
            ->route('admin.useful-link-categories.index')
            ->with('success', 'Useful link category deleted');
    }
}
