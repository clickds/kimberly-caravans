<?php

namespace App\Http\Controllers\Admin\Manufacturer;

use App\Http\Requests\Admin\Manufacturer\CaravanRanges\Clones\StoreRequest;
use App\Models\CaravanRange;
use App\Models\Manufacturer;
use App\Services\Manufacturer\CaravanRange\Caravan\CaravanCloner;
use App\Services\Manufacturer\CaravanRange\CaravanRangeCloner;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class CaravanRangeCloneController extends BaseCaravanRangeController
{
    public function create(Manufacturer $manufacturer, CaravanRange $caravanRange, Request $request): View
    {
        return view('admin.manufacturer.caravan-ranges.clones.create', [
            'manufacturer' => $manufacturer,
            'caravanRange' => $caravanRange,
            'sites' => $this->fetchSites(),
            'redirectUrl' => $this->redirectUrl($request),
            'primaryThemeColours' => $this->primaryThemeColours(),
            'secondaryThemeColours' => $this->secondaryThemeColours(),
        ]);
    }

    public function store(
        StoreRequest $request,
        Manufacturer $manufacturer,
        CaravanRange $caravanRange
    ): RedirectResponse {
        try {
            DB::beginTransaction();

            $rangeCloner = new CaravanRangeCloner($caravanRange, $request->validated());
            $newRange = $rangeCloner->clone();
            $this->updateSitePages($newRange, $newRange->sites->pluck('id')->toArray());

            $caravanCloner = new CaravanCloner($caravanRange, $newRange);
            $caravanCloner->clone();

            DB::commit();

            if ($redirect_url = $this->redirectUrl($request)) {
                return redirect($redirect_url)->with('success', 'Caravan range cloned');
            }

            return redirect()
                ->route('admin.manufacturers.caravan-ranges.index', ['manufacturer' => $manufacturer])
                ->with('success', 'Caravan range cloned');
        } catch (Throwable $e) {
            DB::rollBack();

            Log::error($e);

            return redirect()
                ->back()
                ->withInput($request->all())
                ->with('error', 'Failed to clone caravan range');
        }
    }
}
