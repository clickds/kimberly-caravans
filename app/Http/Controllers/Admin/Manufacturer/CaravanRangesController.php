<?php

namespace App\Http\Controllers\Admin\Manufacturer;

use App\Http\Requests\Admin\Manufacturer\CaravanRanges\StoreRequest;
use App\Http\Requests\Admin\Manufacturer\CaravanRanges\UpdateRequest;
use App\Models\CaravanRange;
use App\Models\Manufacturer;
use App\Models\Page;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CaravanRangesController extends BaseCaravanRangeController
{
    public function index(Request $request, Manufacturer $manufacturer): View
    {
        $caravanRanges = $manufacturer->caravanRanges()
            ->withCount('caravans', 'features', 'specificationSmallPrints')
            ->ransack($request->all())
            ->orderBy('position', 'asc')->get();

        return view('admin.manufacturer.caravan-ranges.index', [
            'manufacturer' => $manufacturer,
            'caravanRanges' => $caravanRanges,
            'listingPages' => $this->getPagesWithTemplate(Page::TEMPLATE_MANUFACTURER_CARAVANS, $manufacturer),
        ]);
    }

    public function create(Manufacturer $manufacturer, Request $request): View
    {
        $caravanRange = $manufacturer->caravanRanges()->make();

        return view('admin.manufacturer.caravan-ranges.create', [
            'manufacturer' => $manufacturer,
            'caravanRange' => $caravanRange,
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
            $caravanRange = $manufacturer->caravanRanges()->create($data);
            $siteIds = $request->get('site_ids', []);
            $caravanRange->sites()->sync($siteIds);
            $this->addImages($caravanRange, $request);
            $this->updateSitePages($caravanRange, $siteIds);
            DB::commit();
        } catch (Throwable $e) {
            DB::rollBack();
            Log::error($e);

            return redirect()
                ->back()
                ->withInput($request->all())
                ->with('error', 'Failed to create caravan range');
        }

        if ($redirect_url = $this->redirectUrl($request)) {
            return redirect($redirect_url)->with('success', 'Caravan range created');
        }

        return redirect()
            ->route('admin.manufacturers.caravan-ranges.index', $manufacturer)
            ->with('success', 'Caravan range created');
    }

    public function edit(Manufacturer $manufacturer, CaravanRange $caravan_range, Request $request): View
    {
        return view('admin.manufacturer.caravan-ranges.edit', [
            'manufacturer' => $manufacturer,
            'caravanRange' => $caravan_range,
            'sites' => $this->fetchSites(),
            'redirectUrl' => $this->redirectUrl($request),
            'primaryThemeColours' => $this->primaryThemeColours(),
            'secondaryThemeColours' => $this->secondaryThemeColours(),
        ]);
    }

    public function update(
        UpdateRequest $request,
        Manufacturer $manufacturer,
        CaravanRange $caravan_range
    ): RedirectResponse {
        $data = $request->validated();
        DB::beginTransaction();
        try {
            $caravan_range->update($data);
            $siteIds = $request->get('site_ids', []);
            $caravan_range->sites()->sync($siteIds);
            $this->addImages($caravan_range, $request);
            $this->updateSitePages($caravan_range, $siteIds);
            DB::commit();
        } catch (Throwable $e) {
            DB::rollBack();
            Log::error($e);

            return redirect()
                ->back()
                ->withInput($request->all())
                ->with('error', 'Failed to update caravan range');
        }

        if ($redirect_url = $this->redirectUrl($request)) {
            return redirect($redirect_url)->with('success', 'Caravan range updated');
        }

        return redirect()
            ->route('admin.manufacturers.caravan-ranges.index', $manufacturer)
            ->with('success', 'Caravan range updated');
    }

    public function destroy(
        Manufacturer $manufacturer,
        CaravanRange $caravan_range,
        Request $request
    ): RedirectResponse {
        $caravan_range->delete();

        if ($redirect_url = $this->redirectUrl($request)) {
            return redirect($redirect_url)->with('success', 'Caravan range deleted');
        }

        return redirect()
            ->route('admin.manufacturers.caravan-ranges.index', $manufacturer)
            ->with('success', 'Caravan range deleted');
    }

    private function addImages(CaravanRange $caravanRange, FormRequest $request): void
    {
        if ($request->hasFile('tab_content_image')) {
            $caravanRange->addMediaFromRequest('tab_content_image')->toMediaCollection('tabContentImage');
        }
        if ($request->hasFile('image')) {
            $caravanRange->addMediaFromRequest('image')->toMediaCollection('mainImage');
        }
        if ($request->hasFile('logo')) {
            $caravanRange->addMediaFromRequest('logo')->toMediaCollection('logo');
        }
    }
}
