<?php

namespace App\Http\Controllers\Admin\CaravanRange;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CaravanRange\RangeSpecificationSmallPrints\StoreRequest;
use App\Http\Requests\Admin\CaravanRange\RangeSpecificationSmallPrints\UpdateRequest;
use App\Models\CaravanRange;
use App\Models\RangeSpecificationSmallPrint;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class RangeSpecificationSmallPrintsController extends Controller
{
    public function index(CaravanRange $caravanRange): View
    {
        $rangeSpecificationSmallPrints = $caravanRange->specificationSmallPrints()
            ->orderBy('position', 'asc')->get();

        return view('admin.caravan-range.range-specification-small-prints.index', [
            'caravanRange' => $caravanRange,
            'rangeSpecificationSmallPrints' => $rangeSpecificationSmallPrints,
        ]);
    }

    public function create(CaravanRange $caravanRange): View
    {
        $rangeSpecificationSmallPrint = $caravanRange->specificationSmallPrints()->make();

        return view('admin.caravan-range.range-specification-small-prints.create', [
            'caravanRange' => $caravanRange,
            'rangeSpecificationSmallPrint' => $rangeSpecificationSmallPrint,
            'sites' => $this->fetchSites(),
        ]);
    }

    public function store(StoreRequest $request, CaravanRange $caravanRange): RedirectResponse
    {
        $data = $request->validated();
        $caravanRange->specificationSmallPrints()->create($data);

        return redirect()->route('admin.caravan-ranges.range-specification-small-prints.index', $caravanRange)
            ->with('success', 'Range specification small print saved');
    }

    public function edit(CaravanRange $caravanRange, RangeSpecificationSmallPrint $rangeSpecificationSmallPrint): View
    {
        return view('admin.caravan-range.range-specification-small-prints.edit', [
            'caravanRange' => $caravanRange,
            'rangeSpecificationSmallPrint' => $rangeSpecificationSmallPrint,
            'sites' => $this->fetchSites(),
        ]);
    }

    public function update(
        UpdateRequest $request,
        CaravanRange $caravanRange,
        RangeSpecificationSmallPrint $rangeSpecificationSmallPrint
    ): RedirectResponse {
        $data = $request->validated();
        $rangeSpecificationSmallPrint->update($data);

        return redirect()->route('admin.caravan-ranges.range-specification-small-prints.index', $caravanRange)
            ->with('success', 'Range specification small print saved');
    }

    public function destroy(
        CaravanRange $caravanRange,
        RangeSpecificationSmallPrint $rangeSpecificationSmallPrint
    ): RedirectResponse {
        $rangeSpecificationSmallPrint->delete();

        return redirect()->route('admin.caravan-ranges.range-specification-small-prints.index', $caravanRange)
            ->with('success', 'Range specification small print deleted');
    }
}
