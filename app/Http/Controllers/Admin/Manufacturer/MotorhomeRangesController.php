<?php

namespace App\Http\Controllers\Admin\Manufacturer;

use App\Http\Requests\Admin\Manufacturer\MotorhomeRanges\StoreRequest;
use App\Http\Requests\Admin\Manufacturer\MotorhomeRanges\UpdateRequest;
use App\Models\MotorhomeRange;
use App\Models\Manufacturer;
use App\Models\Page;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class MotorhomeRangesController extends BaseMotorhomeRangeController
{
    public function index(Request $request, Manufacturer $manufacturer): View
    {
        $motorhomeRanges = $manufacturer->motorhomeRanges()
            ->withCount('features', 'motorhomes', 'specificationSmallPrints')
            ->ransack($request->all())
            ->orderBy('position', 'asc')->get();

        return view('admin.manufacturer.motorhome-ranges.index', [
            'manufacturer' => $manufacturer,
            'motorhomeRanges' => $motorhomeRanges,
            'listingPages' => $this->getPagesWithTemplate(Page::TEMPLATE_MANUFACTURER_MOTORHOMES, $manufacturer),
        ]);
    }

    public function create(Manufacturer $manufacturer, Request $request): View
    {
        $motorhomeRange = $manufacturer->motorhomeRanges()->make();

        return view('admin.manufacturer.motorhome-ranges.create', [
            'manufacturer' => $manufacturer,
            'motorhomeRange' => $motorhomeRange,
            'sites' => $this->fetchSites(),
            'redirectUrl' => $this->redirectUrl($request),
            'primaryThemeColours' => $this->primaryThemeColours(),
            'secondaryThemeColours' => $this->secondaryThemeColours(),
        ]);
    }

    public function store(StoreRequest $request, Manufacturer $manufacturer): RedirectResponse
    {
        $data = $request->validated();
        DB::beginTransaction();
        try {
            $motorhomeRange = $manufacturer->motorhomeRanges()->create($data);
            $siteIds = $request->get('site_ids', []);
            $motorhomeRange->sites()->sync($siteIds);
            $this->addImages($motorhomeRange, $request);
            $this->updateSitePages($motorhomeRange, $siteIds);
            DB::commit();
        } catch (Throwable $e) {
            DB::rollBack();
            Log::error($e);

            return redirect()
                ->back()
                ->withInput($request->all())
                ->with('error', 'Failed to create motorhome range');
        }

        if ($redirect_url = $this->redirectUrl($request)) {
            return redirect($redirect_url)->with('success', 'Motorhome range created');
        }

        return redirect()
            ->route('admin.manufacturers.motorhome-ranges.index', $manufacturer)
            ->with('success', 'Motorhome range created');
    }

    public function edit(Manufacturer $manufacturer, MotorhomeRange $motorhome_range, Request $request): View
    {
        return view('admin.manufacturer.motorhome-ranges.edit', [
            'manufacturer' => $manufacturer,
            'motorhomeRange' => $motorhome_range,
            'sites' => $this->fetchSites(),
            'redirectUrl' => $this->redirectUrl($request),
            'primaryThemeColours' => $this->primaryThemeColours(),
            'secondaryThemeColours' => $this->secondaryThemeColours(),
        ]);
    }

    public function update(
        UpdateRequest $request,
        Manufacturer $manufacturer,
        MotorhomeRange $motorhome_range
    ): RedirectResponse {
        $data = $request->validated();
        DB::beginTransaction();
        try {
            $motorhome_range->update($data);
            $siteIds = $request->get('site_ids', []);
            $motorhome_range->sites()->sync($siteIds);
            $this->addImages($motorhome_range, $request);
            $this->updateSitePages($motorhome_range, $siteIds);
            DB::commit();
        } catch (Throwable $e) {
            Log::error($e);
            DB::rollBack();

            return redirect()
                ->back()
                ->withInput($request->all())
                ->with('error', 'Failed to update motorhome range');
        }

        if ($redirect_url = $this->redirectUrl($request)) {
            return redirect($redirect_url)->with('success', 'Motorhome range updated');
        }

        return redirect()
            ->route('admin.manufacturers.motorhome-ranges.index', $manufacturer)
            ->with('success', 'Motorhome range updated');
    }

    public function destroy(
        Manufacturer $manufacturer,
        MotorhomeRange $motorhome_range,
        Request $request
    ): RedirectResponse {
        $motorhome_range->delete();

        if ($redirect_url = $this->redirectUrl($request)) {
            return redirect($redirect_url)->with('success', 'Motorhome range deleted');
        }

        return redirect()
            ->route('admin.manufacturers.motorhome-ranges.index', $manufacturer)
            ->with('success', 'Motorhome range deleted');
    }

    private function addImages(MotorhomeRange $motorhomeRange, FormRequest $request): void
    {
        if ($request->hasFile('tab_content_image')) {
            $motorhomeRange->addMediaFromRequest('tab_content_image')->toMediaCollection('tabContentImage');
        }
        if ($request->hasFile('image')) {
            $motorhomeRange->addMediaFromRequest('image')->toMediaCollection('mainImage');
        }
        if ($request->hasFile('logo')) {
            $motorhomeRange->addMediaFromRequest('logo')->toMediaCollection('logo');
        }
    }
}
