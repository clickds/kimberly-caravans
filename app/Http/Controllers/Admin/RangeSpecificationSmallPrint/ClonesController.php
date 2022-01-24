<?php

namespace App\Http\Controllers\Admin\RangeSpecificationSmallPrint;

use App\Http\Controllers\Admin\BaseController;
use App\Http\Requests\Admin\RangeSpecificationSmallPrint\Clones\StoreRequest;
use App\Models\CaravanRange;
use App\Models\RangeSpecificationSmallPrint;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ClonesController extends BaseController
{
    public function create(RangeSpecificationSmallPrint $rangeSpecificationSmallPrint): View
    {
        $ignoreSiteIds = [$rangeSpecificationSmallPrint->site_id];

        return view('admin.range-specification-small-prints.clones.create', [
            'rangeSpecificationSmallPrint' => $rangeSpecificationSmallPrint,
            'sites' => $this->fetchSites($ignoreSiteIds),
        ]);
    }

    public function store(
        StoreRequest $request,
        RangeSpecificationSmallPrint $rangeSpecificationSmallPrint
    ): RedirectResponse {
        $redirectUrl = $this->redirectToUrl($rangeSpecificationSmallPrint);

        $clone = $rangeSpecificationSmallPrint->replicate();
        $clone->name = $rangeSpecificationSmallPrint->name . ' Clone';
        $clone->site_id = $request->get('site_id');
        $clone->save();

        return redirect($redirectUrl)->with('success', 'Range specification small print cloned');
    }

    private function redirectToUrl(RangeSpecificationSmallPrint $rangeSpecificationSmallPrint): string
    {
        $range = $rangeSpecificationSmallPrint->vehicleRange;
        switch (get_class($range)) {
            case CaravanRange::class:
                return route('admin.caravan-ranges.range-specification-small-prints.index', $range);
            default:
                return route('admin.motorhome-ranges.range-specification-small-prints.index', $range);
        }
    }
}
