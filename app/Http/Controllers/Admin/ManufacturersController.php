<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Admin\Manufacturers\StoreRequest;
use App\Http\Requests\Admin\Manufacturers\UpdateRequest;
use App\Models\Traits\ImageDeletable;
use App\Models\Manufacturer;
use App\Models\Site;
use App\Services\Pageable\ManufacturerPageSaver;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class ManufacturersController extends BaseController
{
    use ImageDeletable;

    public function index(Request $request): View
    {
        $manufacturers = Manufacturer::withCount(
            'caravanRanges',
            'motorhomeRanges',
            'caravanStockItems',
            'motorhomeStockItems'
        )
            ->ransack($request->all())
            ->orderBy('name', 'asc')
            ->paginate();

        return view('admin.manufacturers.index', [
            'manufacturers' => $manufacturers,
        ]);
    }

    public function create(Request $request): View
    {
        return view('admin.manufacturers.create', [
            'manufacturer' => new Manufacturer(),
            'sites' => Site::all(),
            'redirectUrl' => $this->redirectUrl($request),
            'manufacturers' => Manufacturer::orderBy('name')->get(),
            'linkedManufacturerIds' => [],
        ]);
    }

    public function store(StoreRequest $request): RedirectResponse
    {
        $manufacturer = new Manufacturer();

        if ($this->saveManufacturer($request, $manufacturer)) {
            if ($redirect_url = $this->redirectUrl($request)) {
                return redirect($redirect_url)->with('success', 'Manufacturer created');
            }

            return redirect()
                ->route('admin.manufacturers.index')
                ->with('success', 'Manufacturer created');
        }

        return back()
            ->withInput($request->all())
            ->with('warning', 'Failed to create manufacturer');
    }

    public function edit(Manufacturer $manufacturer, Request $request): View
    {
        $manufacturer->load('sites');

        $linkedManufacturerIds = array_unique(array_merge(
            $manufacturer->linkedByManufacturers->pluck('id')->toArray(),
            $manufacturer->linkedToManufacturers->pluck('id')->toArray()
        ), SORT_REGULAR);

        return view('admin.manufacturers.edit', [
            'manufacturer' => $manufacturer,
            'sites' => Site::all(),
            'redirectUrl' => $this->redirectUrl($request),
            'manufacturers' => Manufacturer::where('id', '!=', $manufacturer->id)->orderBy('name')->get(),
            'linkedManufacturerIds' => $linkedManufacturerIds,
        ]);
    }

    public function update(UpdateRequest $request, Manufacturer $manufacturer): RedirectResponse
    {
        if ($this->saveManufacturer($request, $manufacturer)) {
            if ($redirect_url = $this->redirectUrl($request)) {
                return redirect($redirect_url)->with('success', 'Manufacturer updated');
            }

            return redirect()
                ->route('admin.manufacturers.index')
                ->with('success', 'Manufacturer updated');
        }

        return back()
            ->withInput($request->all())
            ->with('warning', 'Failed to update manufacturer');
    }

    public function destroy(Manufacturer $manufacturer, Request $request): RedirectResponse
    {
        try {
            $manufacturer->delete();

            if ($redirect_url = $this->redirectUrl($request)) {
                return redirect($redirect_url)->with('success', 'Manufacturer deleted');
            }

            return redirect()
                ->route('admin.manufacturers.index')
                ->with('success', 'Manufacturer deleted');
        } catch (Throwable $e) {
            return redirect()
                ->route('admin.manufacturers.index')
                ->with(
                    'warning',
                    'Manufacturer ' . $manufacturer->name . ' has items associated and cannot be deleted'
                );
        }
    }

    private function saveManufacturer(FormRequest $request, Manufacturer $manufacturer): bool
    {
        try {
            DB::beginTransaction();
            $data = $request->validated();
            $manufacturer->fill($data);
            $manufacturer->save();
            $this->syncSites($manufacturer, $request);
            $this->syncLinkedManufacturers($manufacturer, $request);
            $this->addImagesToManufacturer($request, $manufacturer);
            DB::commit();

            return true;
        } catch (Throwable $e) {
            Log::error($e);
            DB::rollBack();

            return false;
        }
    }

    private function manufacturerData(Request $request): array
    {
        return $request->only('exclusive', 'name', 'motorhome_position', 'caravan_position', 'exclusive');
    }

    private function syncSites(Manufacturer $manufacturer, Request $request): void
    {
        $siteIds = $request->get('site_ids', []);
        $manufacturer->sites()->sync($siteIds);
        $this->updateSitePages($manufacturer, $siteIds);
    }

    private function syncLinkedManufacturers(Manufacturer $manufacturer, Request $request): void
    {
        $linkedManufacturerIds = $request->get('linked_manufacturer_ids', []);
        $manufacturer->linkedToManufacturers()->sync($linkedManufacturerIds);
        $manufacturer->linkedByManufacturers()->sync($linkedManufacturerIds);
    }

    private function updateSitePages(Manufacturer $manufacturer, array $siteIds): void
    {
        $manufacturer->pages()->whereNotIn('site_id', $siteIds)->delete();
        $sites = Site::whereIn('id', $siteIds)->get();
        foreach ($sites as $site) {
            $saver = new ManufacturerPageSaver($manufacturer, $site);
            $saver->call();
        }
    }

    private function addImagesToManufacturer(FormRequest $request, Manufacturer $manufacturer): void
    {
        $imageMappings = [
            'caravan_image' => 'caravanImage',
            'motorhome_image' => 'motorhomeImage',
            'logo' => 'logo',
        ];

        foreach ($imageMappings as $inputName => $collectionName) {
            if ($request->hasFile($inputName)) {
                $manufacturer->addMediaFromRequest($inputName)->toMediaCollection($collectionName);
            }
        }
    }
}
