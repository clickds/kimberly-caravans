<?php

namespace App\Http\Controllers\Admin\CaravanRange;

use Illuminate\Http\Request;
use App\Http\Requests\Admin\CaravanRange\Caravans\StoreRequest;
use App\Http\Requests\Admin\CaravanRange\Caravans\UpdateRequest;
use App\Events\CaravanSaved;
use App\Http\Controllers\Admin\BaseController;
use App\Models\Berth;
use App\Models\Caravan;
use App\Models\CaravanRange;
use App\Models\Layout;
use App\Models\Page;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class CaravansController extends BaseController
{
    public function index(Request $request, CaravanRange $caravanRange): View
    {
        $caravans = $caravanRange->caravans()->withCount('bedSizes')
            ->ransack($request->all())
            ->orderBy('position', 'asc')->get();

        return view('admin.caravan-range.caravans.index', [
            'caravanRange' => $caravanRange,
            'caravans' => $caravans,
            'listingPages' => $this->getPagesWithTemplate(Page::TEMPLATE_CARAVAN_RANGE, $caravanRange),
        ]);
    }

    public function create(CaravanRange $caravanRange, Request $request): View
    {
        $caravan = $caravanRange->caravans()->make();

        return view('admin.caravan-range.caravans.create', [
            'berths' => $this->fetchBerths(),
            'caravanRange' => $caravanRange,
            'caravan' => $caravan,
            'axles' => $this->fetchAxles(),
            'layouts' => $this->fetchLayouts(),
            'sites' => $this->fetchSites(),
            'rangeGalleryImages' => $caravanRange->galleryImages()->get(),
            'stockItemImageIds' => [],
            'videos' => $this->fetchCaravanRangeVideos($caravanRange),
            'redirectUrl' => $this->redirectUrl($request),
        ]);
    }

    public function store(StoreRequest $request, CaravanRange $caravanRange): RedirectResponse
    {
        DB::beginTransaction();
        try {
            $data = $request->validated();
            $caravan = $caravanRange->caravans()->create($data);
            $this->syncSites($caravan, $request);
            $caravan->berths()->sync($request->input('berth_ids', []));
            $this->addImages($caravan, $request);
            $this->syncStockItemImages($caravan, $request);

            DB::commit();

            $this->dispatchEvents($caravan);
        } catch (Throwable $e) {
            Log::error($e);
            DB::rollback();

            return redirect()
                ->back()
                ->withInput($request->all())
                ->with('error', 'Failed to create caravan');
        }

        if ($redirect_url = $this->redirectUrl($request)) {
            return redirect($redirect_url)->with('success', 'Caravan created');
        }

        return redirect()
            ->route('admin.caravan-ranges.caravans.index', $caravanRange)
            ->with('success', 'Caravan created');
    }

    public function edit(CaravanRange $caravanRange, Caravan $caravan, Request $request): View
    {
        return view('admin.caravan-range.caravans.edit', [
            'berths' => $this->fetchBerths(),
            'caravanRange' => $caravanRange,
            'caravan' => $caravan,
            'axles' => $this->fetchAxles(),
            'layouts' => $this->fetchLayouts(),
            'sites' => $this->fetchSites(),
            'rangeVideos' => $caravanRange->videos(),
            'rangeGalleryImages' => $caravanRange->galleryImages()->get(),
            'stockItemImageIds' => $caravan->stockItemImages()->pluck('id')->toArray(),
            'videos' => $this->fetchCaravanRangeVideos($caravanRange),
            'redirectUrl' => $this->redirectUrl($request),
        ]);
    }

    public function update(UpdateRequest $request, CaravanRange $caravanRange, Caravan $caravan): RedirectResponse
    {
        DB::beginTransaction();
        try {
            $data = $request->validated();
            $caravan->update($data);
            $caravan->berths()->sync($request->input('berth_ids', []));
            $this->syncSites($caravan, $request);
            $this->addImages($caravan, $request);
            $this->syncStockItemImages($caravan, $request);

            DB::commit();

            $this->dispatchEvents($caravan);
        } catch (Throwable $e) {
            Log::error($e);
            DB::rollback();

            return redirect()
                ->back()
                ->withInput($request->all())
                ->with('error', 'Failed to create caravan');
        }

        if ($redirect_url = $this->redirectUrl($request)) {
            return redirect($redirect_url)->with('success', 'Caravan updated');
        }

        return redirect()
            ->route('admin.caravan-ranges.caravans.index', $caravanRange)
            ->with('success', 'Caravan updated');
    }

    public function destroy(CaravanRange $caravanRange, Caravan $caravan, Request $request): RedirectResponse
    {
        DB::beginTransaction();
        try {
            if ($stockItem = $caravan->stockItem) {
                $stockItem->delete();
            }
            $caravan->delete();
            DB::commit();

            if ($redirect_url = $this->redirectUrl($request)) {
                return redirect($redirect_url)->with('success', 'Caravan deleted');
            }

            return redirect()
                ->route('admin.caravan-ranges.caravans.index', $caravanRange)
                ->with('success', 'Caravan deleted');
        } catch (Throwable $e) {
            Log::error($e);
            DB::rollBack();

            return redirect()
                ->back()
                ->with('error', 'Failed to delete caravan');
        }
    }

    private function fetchCaravanRangeVideos(CaravanRange $caravanRange): Collection
    {
        return $caravanRange->videos()->select('id', 'title')->get();
    }

    private function syncSites(Caravan $caravan, FormRequest $request): void
    {
        $sitesData = collect($request->get('sites'))->filter(function ($siteData) {
            return array_key_exists('id', $siteData);
        })->mapWithKeys(function ($siteData) {
            // If there is no price, set it the same as the recommended price.
            if (array_key_exists('price', $siteData) && array_key_exists('recommended_price', $siteData)) {
                $siteData['price'] = $siteData['price'] ?? $siteData['recommended_price'];
            }
            return [$siteData['id'] => Arr::except($siteData, 'id')];
        });

        $caravan->sites()->sync($sitesData);
    }

    private function dispatchEvents(Caravan $caravan): void
    {
        event(new CaravanSaved($caravan));
    }

    private function addImages(Caravan $caravan, FormRequest $request): void
    {
        $inputMappings = [
            'day_floorplan' => 'dayFloorplan',
            'night_floorplan' => 'nightFloorplan',
        ];

        foreach ($inputMappings as $inputName => $collectionName) {
            if ($request->hasFile($inputName)) {
                $caravan->addMediaFromRequest($inputName)->toMediaCollection($collectionName);
            }
        }
    }

    private function syncStockItemImages(Caravan $caravan, FormRequest $request): void
    {
        $ids = $request->input('stock_item_image_ids', []);
        $caravan->stockItemImages()->sync($ids);
    }

    private function fetchAxles(): array
    {
        return Caravan::AXLES;
    }

    private function fetchLayouts(): Collection
    {
        return Layout::orderBy('code', 'asc')->get();
    }

    private function fetchBerths(): Collection
    {
        return Berth::orderBy('number', 'asc')->get();
    }
}
