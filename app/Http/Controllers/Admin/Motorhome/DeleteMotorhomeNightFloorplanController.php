<?php

namespace App\Http\Controllers\Admin\Motorhome;

use App\Http\Controllers\Controller;
use App\Models\Motorhome;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use Throwable;

class DeleteMotorhomeNightFloorplanController extends Controller
{
    public function __invoke(Motorhome $motorhome): RedirectResponse
    {
        try {
            $nightFLoorplan = $motorhome->getFirstMedia('nightFloorplan');

            if (is_null($nightFLoorplan)) {
                return redirect()->back()->with('success', 'Successfully deleted motorhome night floorplan');
            }

            $nightFLoorplan->delete();

            return redirect()->back()->with('success', 'Successfully deleted motorhome night floorplan');
        } catch (Throwable $e) {
            Log::error($e);

            return redirect()->back()->with('error', 'Failed to delete motorhome night floorplan');
        }
    }
}
