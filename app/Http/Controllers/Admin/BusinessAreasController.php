<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\BusinessAreas\StoreRequest;
use App\Http\Requests\Admin\BusinessAreas\UpdateRequest;
use App\Models\BusinessArea;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Throwable;

class BusinessAreasController extends BaseController
{
    public function index(): View
    {
        return view('admin.business-areas.index', [
            'businessAreas' => BusinessArea::orderBy('name', 'asc')->get(),
        ]);
    }

    public function create(Request $request): View
    {
        return view('admin.business-areas.create', [
            'businessArea' => new BusinessArea(),
            'redirectUrl' => $this->redirectUrl($request),
        ]);
    }

    public function store(StoreRequest $request): RedirectResponse
    {
        $data = $request->validated();

        BusinessArea::create($data);

        if ($redirect_url = $this->redirectUrl($request)) {
            return redirect($redirect_url)->with('success', 'Business area created');
        }

        return redirect()
            ->route('admin.business-areas.index')
            ->with('success', 'Business area created');
    }

    public function edit(BusinessArea $business_area, Request $request): View
    {
        return view('admin.business-areas.edit', [
            'businessArea' => $business_area,
            'redirectUrl' => $this->redirectUrl($request),
        ]);
    }

    public function update(UpdateRequest $request, BusinessArea $business_area): RedirectResponse
    {
        $data = $request->validated();
        $business_area->update($data);

        if ($redirect_url = $this->redirectUrl($request)) {
            return redirect($redirect_url)->with('success', 'Business area updated');
        }

        return redirect()
            ->route('admin.business-areas.index')
            ->with('success', 'Business area updated');
    }

    public function destroy(BusinessArea $business_area, Request $request): RedirectResponse
    {
        try {
            $business_area->delete();

            if ($redirect_url = $this->redirectUrl($request)) {
                return redirect($redirect_url)->with('success', 'Business area deleted');
            }

            return redirect()
                ->route('admin.business-areas.index')
                ->with('success', 'Business area deleted');
        } catch (Throwable $e) {
            return redirect()
                ->back()
                ->with('warning', 'Failed to delete business area');
        }
    }
}
