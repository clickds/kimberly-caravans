<?php

namespace App\Http\Controllers\Admin\RangeFeature;

use App\Http\Controllers\Controller;
use App\Models\CaravanRange;
use App\Models\RangeFeature;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

class ClonesController extends Controller
{
    public function store(RangeFeature $rangeFeature): RedirectResponse
    {
        DB::beginTransaction();
        try {
            $clone = $rangeFeature->replicate();
            $clone->name = $rangeFeature->name . ' Clone';
            $clone->save();
            $siteIds = $rangeFeature->sites()->pluck('id')->toArray();
            $clone->sites()->attach($siteIds);
            DB::commit();

            return $this->calculateRedirect($clone);
        } catch (Throwable $e) {
            DB::rollBack();
            Log::error($e);

            return redirect()->back()->with('warning', 'Could not clone range');
        }
    }

    private function calculateRedirect(RangeFeature $rangeFeature): RedirectResponse
    {
        $vehicleRange = $rangeFeature->vehicleRange;
        if (get_class($vehicleRange) == CaravanRange::class) {
            return redirect()->route('admin.caravan-ranges.range-features.edit', [
                'caravanRange' => $vehicleRange,
                'range_feature' => $rangeFeature,
            ])->with('success', 'Feature cloned');
        }

        return redirect()->route('admin.motorhome-ranges.range-features.edit', [
            'motorhomeRange' => $vehicleRange,
            'range_feature' => $rangeFeature,
        ])->with('success', 'Feature cloned');
    }
}
