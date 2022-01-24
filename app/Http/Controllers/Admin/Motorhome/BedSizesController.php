<?php

namespace App\Http\Controllers\Admin\Motorhome;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Motorhome\BedSizes\StoreRequest;
use App\Http\Requests\Admin\Motorhome\BedSizes\UpdateRequest;
use App\Models\BedDescription;
use App\Models\BedSize;
use App\Models\Motorhome;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class BedSizesController extends Controller
{
    public function index(Motorhome $motorhome): View
    {
        $bedSizes = $motorhome->bedSizes()->with('bedDescription')->get();

        return view('admin.motorhome.bed-sizes.index', [
            'motorhome' => $motorhome,
            'bedSizes' => $bedSizes,
        ]);
    }

    public function create(Motorhome $motorhome): View
    {
        $bedSize = $motorhome->bedSizes()->make();

        return view('admin.motorhome.bed-sizes.create', [
            'motorhome' => $motorhome,
            'bedDescriptions' => $this->fetchBedDescriptions(),
            'bedSize' => $bedSize,
        ]);
    }

    public function store(StoreRequest $request, Motorhome $motorhome): RedirectResponse
    {
        $data = $request->validated();
        $motorhome->bedSizes()->create($data);

        return redirect()->route('admin.motorhomes.bed-sizes.index', $motorhome)
            ->with('success', 'Bed size saved');
    }

    public function edit(Motorhome $motorhome, BedSize $bedSize): View
    {
        return view('admin.motorhome.bed-sizes.edit', [
            'motorhome' => $motorhome,
            'bedDescriptions' => $this->fetchBedDescriptions(),
            'bedSize' => $bedSize,
        ]);
    }

    public function update(UpdateRequest $request, Motorhome $motorhome, BedSize $bedSize): RedirectResponse
    {
        $data = $request->validated();
        $bedSize->update($data);

        return redirect()->route('admin.motorhomes.bed-sizes.index', $motorhome)
            ->with('success', 'Bed size saved');
    }

    public function destroy(Motorhome $motorhome, BedSize $bedSize): RedirectResponse
    {
        $bedSize->delete();

        return redirect()->route('admin.motorhomes.bed-sizes.index', $motorhome)
            ->with('success', 'Deleted bed size');
    }

    private function fetchBedDescriptions(): Collection
    {
        return BedDescription::orderBy('name', 'asc')->select('id', 'name')->get();
    }
}
