<?php

namespace App\Http\Controllers\Admin\MotorhomeRange;

use App\Events\MotorhomeSaved;
use App\Http\Controllers\Admin\BaseController;
use Illuminate\Http\Request;
use App\Http\Requests\Admin\MotorhomeRange\Motorhomes\StoreRequest;
use App\Http\Requests\Admin\MotorhomeRange\Motorhomes\UpdateRequest;
use App\Models\Berth;
use App\Models\Motorhome;
use App\Models\MotorhomeRange;
use App\Models\Layout;
use App\Models\Page;
use App\Models\Seat;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class MotorhomesController extends BaseController
{
    public function index(Request $request, MotorhomeRange $motorhomeRange): View
    {
        $motorhomes = $motorhomeRange->motorhomes()
            ->withCount('bedSizes', 'optionalWeights')
            ->ransack($request->all())
            ->orderBy('position', 'asc')->get();

        return view('admin.motorhome-range.motorhomes.index', [
            'motorhomeRange' => $motorhomeRange,
            'motorhomes' => $motorhomes,
            'listingPages' => $this->getPagesWithTemplate(Page::TEMPLATE_MOTORHOME_RANGE, $motorhomeRange),
        ]);
    }

    public function create(MotorhomeRange $motorhomeRange, Request $request): View
    {
        $motorhome = $motorhomeRange->motorhomes()->make();

        return view('admin.motorhome-range.motorhomes.create', [
            'berths' => $this->fetchBerths(),
            'seats' => $this->fetchSeats(),
            'motorhomeRange' => $motorhomeRange,
            'motorhome' => $motorhome,
            'fuels' => $this->fetchFuels(),
            'transmissions' => $this->fetchTransmissions(),
            'conversions' => $this->fetchConversions(),
            'layouts' => $this->fetchLayouts(),
            'sites' => $this->fetchSites(),
            'rangeGalleryImages' => $motorhomeRange->galleryImages()->get(),
            'stockItemImageIds' => $motorhome->stockItemImages()->pluck('id')->toArray(),
            'videos' => $this->fetchMotorhomeRangeVideos($motorhomeRange),
            'redirectUrl' => $this->redirectUrl($request),
        ]);
    }

    public function store(StoreRequest $request, MotorhomeRange $motorhomeRange): RedirectResponse
    {
        DB::beginTransaction();
        try {
            $data = $request->validated();
            $motorhome = $motorhomeRange->motorhomes()->create($data);
            $motorhome->berths()->sync($request->input('berth_ids', []));
            $motorhome->seats()->sync($request->input('seat_ids', []));
            $siteIds = $this->syncSites($motorhome, $request);
            $this->addImages($motorhome, $request);
            $this->syncStockItemImages($motorhome, $request);

            DB::commit();
            $this->dispatchEvents($motorhome);
        } catch (Throwable $e) {
            Log::error($e);
            DB::rollback();

            return redirect()
                ->back()
                ->withInput($request->all())
                ->with('error', 'Failed to create motorhome');
        }

        if ($redirect_url = $this->redirectUrl($request)) {
            return redirect($redirect_url)->with('success', 'Motorhome created');
        }

        return redirect()
            ->route('admin.motorhome-ranges.motorhomes.index', $motorhomeRange)
            ->with('success', 'Motorhome created');
    }

    public function edit(MotorhomeRange $motorhomeRange, Motorhome $motorhome, Request $request): View
    {
        return view('admin.motorhome-range.motorhomes.edit', [
            'berths' => $this->fetchBerths(),
            'seats' => $this->fetchSeats(),
            'motorhomeRange' => $motorhomeRange,
            'motorhome' => $motorhome,
            'fuels' => $this->fetchFuels(),
            'transmissions' => $this->fetchTransmissions(),
            'conversions' => $this->fetchConversions(),
            'layouts' => $this->fetchLayouts(),
            'sites' => $this->fetchSites(),
            'rangeVideos' => $motorhomeRange->videos(),
            'rangeGalleryImages' => $motorhomeRange->galleryImages()->get(),
            'stockItemImageIds' => $motorhome->stockItemImages()->pluck('id')->toArray(),
            'videos' => $this->fetchMotorhomeRangeVideos($motorhomeRange),
            'redirectUrl' => $this->redirectUrl($request),
        ]);
    }

    public function update(
        UpdateRequest $request,
        MotorhomeRange $motorhomeRange,
        Motorhome $motorhome
    ): RedirectResponse {
        DB::beginTransaction();
        try {
            $data = $request->validated();
            $motorhome->update($data);
            $motorhome->berths()->sync($request->input('berth_ids', []));
            $motorhome->seats()->sync($request->input('seat_ids', []));
            $siteIds = $this->syncSites($motorhome, $request);
            $this->addImages($motorhome, $request);
            $this->syncStockItemImages($motorhome, $request);

            DB::commit();
            $this->dispatchEvents($motorhome);

            if ($redirect_url = $this->redirectUrl($request)) {
                return redirect($redirect_url)->with('success', 'Motorhome updated');
            }

            return redirect()
                ->route('admin.motorhome-ranges.motorhomes.index', $motorhomeRange)
                ->with('success', 'Motorhome updated');
        } catch (Throwable $e) {
            Log::error($e);
            DB::rollback();

            return redirect()
                ->back()
                ->with('error', 'Failed to update motorhome');
        }
    }

    public function destroy(MotorhomeRange $motorhomeRange, Motorhome $motorhome, Request $request): RedirectResponse
    {
        DB::beginTransaction();
        try {
            if ($stockItem = $motorhome->stockItem) {
                $stockItem->delete();
            }
            $motorhome->delete();
            DB::commit();

            if ($redirect_url = $this->redirectUrl($request)) {
                return redirect($redirect_url)->with('success', 'Motorhome deleted');
            }

            return redirect()
                ->route('admin.motorhome-ranges.motorhomes.index', $motorhomeRange)
                ->with('success', 'Motorhome deleted');
        } catch (Throwable $e) {
            Log::error($e);
            DB::rollBack();

            return redirect()
                ->back()
                ->with('error', 'Failed to delete motorhome');
        }
    }

    private function syncSites(Motorhome $motorhome, FormRequest $request): array
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

        $motorhome->sites()->sync($sitesData);

        // Return the site ids
        return $sitesData->keys()->toArray();
    }

    private function addImages(Motorhome $motorhome, FormRequest $request): void
    {
        if ($request->hasFile('day_floorplan')) {
            $motorhome->addMediaFromRequest('day_floorplan')->toMediaCollection('dayFloorplan');
        }
        if ($request->hasFile('night_floorplan')) {
            $motorhome->addMediaFromRequest('night_floorplan')->toMediaCollection('nightFloorplan');
        }
    }

    private function syncStockItemImages(Motorhome $motorhome, FormRequest $request): void
    {
        $ids = $request->input('stock_item_image_ids', []);
        $motorhome->stockItemImages()->sync($ids);
    }

    private function dispatchEvents(Motorhome $motorhome): void
    {
        event(new MotorhomeSaved($motorhome));
    }

    private function fetchMotorhomeRangeVideos(MotorhomeRange $motorhomeRange): Collection
    {
        return $motorhomeRange->videos()->select('id', 'title')->get();
    }

    private function fetchFuels(): array
    {
        return Motorhome::FUELS;
    }

    private function fetchTransmissions(): array
    {
        return Motorhome::TRANSMISSIONS;
    }

    private function fetchConversions(): array
    {
        return Motorhome::CONVERSIONS;
    }

    private function fetchLayouts(): Collection
    {
        return Layout::orderBy('code', 'asc')->get();
    }

    private function fetchBerths(): Collection
    {
        return Berth::orderBy('number', 'asc')->get();
    }

    private function fetchSeats(): Collection
    {
        return Seat::orderBy('number', 'asc')->get();
    }
}
