<?php

namespace App\Http\Controllers\Admin\Caravan;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Caravan\BedSizes\StoreRequest;
use App\Http\Requests\Admin\Caravan\BedSizes\UpdateRequest;
use App\Models\BedDescription;
use App\Models\BedSize;
use App\Models\Caravan;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class BedSizesController extends Controller
{
    public function index(Caravan $caravan): View
    {
        $bedSizes = $caravan->bedSizes()->with('bedDescription')->get();

        return view('admin.caravan.bed-sizes.index', [
            'caravan' => $caravan,
            'bedSizes' => $bedSizes,
        ]);
    }

    public function create(Caravan $caravan): View
    {
        $bedSize = $caravan->bedSizes()->make();

        return view('admin.caravan.bed-sizes.create', [
            'caravan' => $caravan,
            'bedDescriptions' => $this->fetchBedDescriptions(),
            'bedSize' => $bedSize,
        ]);
    }

    public function store(StoreRequest $request, Caravan $caravan): RedirectResponse
    {
        $data = $request->validated();
        $caravan->bedSizes()->create($data);

        return redirect()->route('admin.caravans.bed-sizes.index', $caravan)
            ->with('success', 'Bed size saved');
    }

    public function edit(Caravan $caravan, BedSize $bedSize): View
    {
        return view('admin.caravan.bed-sizes.edit', [
            'caravan' => $caravan,
            'bedDescriptions' => $this->fetchBedDescriptions(),
            'bedSize' => $bedSize,
        ]);
    }

    public function update(UpdateRequest $request, Caravan $caravan, BedSize $bedSize): RedirectResponse
    {
        $data = $request->validated();
        $bedSize->update($data);

        return redirect()->route('admin.caravans.bed-sizes.index', $caravan)
            ->with('success', 'Bed size saved');
    }

    public function destroy(Caravan $caravan, BedSize $bedSize): RedirectResponse
    {
        $bedSize->delete();

        return redirect()->route('admin.caravans.bed-sizes.index', $caravan)
            ->with('success', 'Deleted bed size');
    }

    private function fetchBedDescriptions(): Collection
    {
        return BedDescription::orderBy('name', 'asc')->select('id', 'name')->get();
    }
}
