<?php

namespace App\Http\Controllers\Admin\MotorhomeRange;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\MotorhomeRange\RangeSpecificationSmallPrints\StoreRequest;
use App\Http\Requests\Admin\MotorhomeRange\RangeSpecificationSmallPrints\UpdateRequest;
use App\Models\MotorhomeRange;
use App\Models\RangeSpecificationSmallPrint;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class RangeSpecificationSmallPrintsController extends Controller
{
    public function index(MotorhomeRange $motorhomeRange): View
    {
        $rangeSpecificationSmallPrints = $motorhomeRange->specificationSmallPrints()
            ->orderBy('position', 'asc')->get();

        return view('admin.motorhome-range.range-specification-small-prints.index', [
            'motorhomeRange' => $motorhomeRange,
            'rangeSpecificationSmallPrints' => $rangeSpecificationSmallPrints,
        ]);
    }

    public function create(MotorhomeRange $motorhomeRange): View
    {
        $rangeSpecificationSmallPrint = $motorhomeRange->specificationSmallPrints()->make();

        return view('admin.motorhome-range.range-specification-small-prints.create', [
            'motorhomeRange' => $motorhomeRange,
            'rangeSpecificationSmallPrint' => $rangeSpecificationSmallPrint,
            'sites' => $this->fetchSites(),
        ]);
    }

    public function store(StoreRequest $request, MotorhomeRange $motorhomeRange): RedirectResponse
    {
        $data = $request->validated();
        $motorhomeRange->specificationSmallPrints()->create($data);

        return redirect()->route('admin.motorhome-ranges.range-specification-small-prints.index', $motorhomeRange)
            ->with('success', 'Range specification small print saved');
    }

    public function edit(
        MotorhomeRange $motorhomeRange,
        RangeSpecificationSmallPrint $rangeSpecificationSmallPrint
    ): View {
        return view('admin.motorhome-range.range-specification-small-prints.edit', [
            'motorhomeRange' => $motorhomeRange,
            'rangeSpecificationSmallPrint' => $rangeSpecificationSmallPrint,
            'sites' => $this->fetchSites(),
        ]);
    }

    public function update(
        UpdateRequest $request,
        MotorhomeRange $motorhomeRange,
        RangeSpecificationSmallPrint $rangeSpecificationSmallPrint
    ): RedirectResponse {
        $data = $request->validated();
        $rangeSpecificationSmallPrint->update($data);

        return redirect()->route('admin.motorhome-ranges.range-specification-small-prints.index', $motorhomeRange)
            ->with('success', 'Range specification small print saved');
    }

    public function destroy(
        MotorhomeRange $motorhomeRange,
        RangeSpecificationSmallPrint $rangeSpecificationSmallPrint
    ): RedirectResponse {
        $rangeSpecificationSmallPrint->delete();

        return redirect()->route('admin.motorhome-ranges.range-specification-small-prints.index', $motorhomeRange)
            ->with('success', 'Range specification small print deleted');
    }
}
