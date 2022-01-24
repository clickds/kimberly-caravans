<?php

namespace App\Http\Controllers\Admin\Caravan;

use App\Http\Controllers\Controller;
use App\Models\Caravan;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use Throwable;

class DeleteCaravanNightFloorplanController extends Controller
{
    public function __invoke(Caravan $caravan): RedirectResponse
    {
        try {
            $nightFLoorplan = $caravan->getFirstMedia('nightFloorplan');

            if (is_null($nightFLoorplan)) {
                return redirect()->back()->with('success', 'Successfully deleted caravan night floorplan');
            }

            $nightFLoorplan->delete();

            return redirect()->back()->with('success', 'Successfully deleted caravan night floorplan');
        } catch (Throwable $e) {
            Log::error($e);

            return redirect()->back()->with('error', 'Failed to delete caravan night floorplan');
        }
    }
}
