<?php

namespace App\Http\Controllers\Admin\Motorhome;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Motorhome\OptionalWeights\StoreRequest;
use App\Http\Requests\Admin\Motorhome\OptionalWeights\UpdateRequest;
use App\Models\Motorhome;
use App\Models\OptionalWeight;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class OptionalWeightsController extends Controller
{
    public function index(Motorhome $motorhome): View
    {
        $optionalWeights = $motorhome->optionalWeights()->get();

        return view('admin.motorhome.optional-weights.index', [
            'motorhome' => $motorhome,
            'optionalWeights' => $optionalWeights,
        ]);
    }

    public function create(Motorhome $motorhome): View
    {
        $optionalWeight = $motorhome->optionalWeights()->make();

        return view('admin.motorhome.optional-weights.create', [
            'motorhome' => $motorhome,
            'optionalWeight' => $optionalWeight,
        ]);
    }

    public function store(StoreRequest $request, Motorhome $motorhome): RedirectResponse
    {
        $data = $request->validated();
        $motorhome->optionalWeights()->create($data);

        return redirect()->route('admin.motorhomes.optional-weights.index', $motorhome)
            ->with('success', 'Optional weight saved');
    }

    public function edit(Motorhome $motorhome, OptionalWeight $optionalWeight): View
    {
        return view('admin.motorhome.optional-weights.edit', [
            'motorhome' => $motorhome,
            'optionalWeight' => $optionalWeight,
        ]);
    }

    public function update(
        UpdateRequest $request,
        Motorhome $motorhome,
        OptionalWeight $optionalWeight
    ): RedirectResponse {
        $data = $request->validated();
        $optionalWeight->update($data);

        return redirect()->route('admin.motorhomes.optional-weights.index', $motorhome)
            ->with('success', 'Optional weight saved');
    }

    public function destroy(Motorhome $motorhome, OptionalWeight $optionalWeight): RedirectResponse
    {
        $optionalWeight->delete();

        return redirect()->route('admin.motorhomes.optional-weights.index', $motorhome)
            ->with('success', 'Optional weight deleted');
    }
}
