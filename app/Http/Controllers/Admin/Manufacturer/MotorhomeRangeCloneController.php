<?php

namespace App\Http\Controllers\Admin\Manufacturer;

use App\Http\Requests\Admin\Manufacturer\MotorhomeRanges\Clones\StoreRequest;
use App\Models\Manufacturer;
use App\Models\MotorhomeRange;
use App\Services\Manufacturer\MotorhomeRange\Motorhome\MotorhomeCloner;
use App\Services\Manufacturer\MotorhomeRange\MotorhomeRangeCloner;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class MotorhomeRangeCloneController extends BaseMotorhomeRangeController
{
    public function create(Manufacturer $manufacturer, MotorhomeRange $motorhomeRange, Request $request): View
    {
        return view('admin.manufacturer.motorhome-ranges.clones.create', [
            'manufacturer' => $manufacturer,
            'motorhomeRange' => $motorhomeRange,
            'sites' => $this->fetchSites(),
            'redirectUrl' => $this->redirectUrl($request),
            'primaryThemeColours' => $this->primaryThemeColours(),
            'secondaryThemeColours' => $this->secondaryThemeColours(),
        ]);
    }

    public function store(
        StoreRequest $request,
        Manufacturer $manufacturer,
        MotorhomeRange $motorhomeRange
    ): RedirectResponse {
        try {
            DB::beginTransaction();

            $rangeCloner = new MotorhomeRangeCloner($motorhomeRange, $request->validated());
            $newRange = $rangeCloner->clone();

            $motorhomeCloner = new MotorhomeCloner($motorhomeRange, $newRange);
            $motorhomeCloner->clone();

            $this->updateSitePages($newRange, $newRange->sites->pluck('id')->toArray());

            DB::commit();

            if ($redirect_url = $this->redirectUrl($request)) {
                return redirect($redirect_url)->with('success', 'Motorhome range cloned');
            }

            return redirect()
                ->route('admin.manufacturers.motorhome-ranges.index', ['manufacturer' => $manufacturer])
                ->with('success', 'Motorhome range cloned');
        } catch (Throwable $e) {
            DB::rollBack();

            Log::error($e);

            return redirect()
                ->back()
                ->withInput($request->all())
                ->with('error', 'Failed to clone motorhome range');
        }
    }
}
