<?php

namespace App\Http\Controllers\Admin\CaravanRange;

use App\Http\Controllers\Admin\BaseController;
use App\Http\Requests\Admin\CaravanRange\RangeFeatures\StoreRequest;
use App\Http\Requests\Admin\CaravanRange\RangeFeatures\UpdateRequest;
use App\Models\CaravanRange;
use App\Models\RangeFeature;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class RangeFeaturesController extends BaseController
{
    public function index(Request $request, CaravanRange $caravanRange): View
    {
        $rangeFeatures = $caravanRange->features()
            ->ransack($request->all())
            ->orderBy('position', 'asc')->get();

        return view('admin.caravan-range.range-features.index', [
            'caravanRange' => $caravanRange,
            'rangeFeatures' => $rangeFeatures,
        ]);
    }

    public function create(CaravanRange $caravanRange, Request $request): View
    {
        $rangeFeature = $caravanRange->features()->make();

        return view('admin.caravan-range.range-features.create', [
            'caravanRange' => $caravanRange,
            'rangeFeature' => $rangeFeature,
            'sites' => $this->fetchSites(),
            'redirectUrl' => $this->redirectUrl($request),
        ]);
    }

    public function store(StoreRequest $request, CaravanRange $caravanRange): RedirectResponse
    {
        $rangeFeature = $caravanRange->features()->make();

        if ($this->saveRangeFeature($rangeFeature, $request)) {
            if ($redirect_url = $this->redirectUrl($request)) {
                return redirect($redirect_url)->with('success', 'Feature created');
            }

            return redirect()
                ->route('admin.caravan-ranges.range-features.index', $caravanRange)
                ->with('success', 'Feature created');
        }

        return redirect()
            ->back()
            ->withInput($request->all())
            ->with('error', 'Failed to create feature');
    }

    public function edit(CaravanRange $caravanRange, RangeFeature $rangeFeature, Request $request): View
    {
        return view('admin.caravan-range.range-features.edit', [
            'caravanRange' => $caravanRange,
            'rangeFeature' => $rangeFeature,
            'sites' => $this->fetchSites(),
            'redirectUrl' => $this->redirectUrl($request),
        ]);
    }

    public function update(
        UpdateRequest $request,
        CaravanRange $caravanRange,
        RangeFeature $rangeFeature
    ): RedirectResponse {
        if ($this->saveRangeFeature($rangeFeature, $request)) {
            if ($redirect_url = $this->redirectUrl($request)) {
                return redirect($redirect_url)->with('success', 'Feature updated');
            }

            return redirect()
                ->route('admin.caravan-ranges.range-features.index', $caravanRange)
                ->with('success', 'Feature updated');
        }

        return redirect()
            ->back()
            ->withInput($request->all())
            ->with('error', 'Failed to update feature');
    }

    public function destroy(
        CaravanRange $caravanRange,
        RangeFeature $rangeFeature,
        Request $request
    ): RedirectResponse {
        $rangeFeature->delete();

        if ($redirect_url = $this->redirectUrl($request)) {
            return redirect($redirect_url)->with('success', 'Feature deleted');
        }

        return redirect()
            ->route('admin.caravan-ranges.range-features.index', $caravanRange)
            ->with('success', 'Feature deleted');
    }

    private function saveRangeFeature(RangeFeature $rangeFeature, FormRequest $request): bool
    {
        DB::beginTransaction();
        try {
            $data = $request->validated();
            $rangeFeature->fill($data);
            $rangeFeature->save();
            $rangeFeature->sites()->sync($request->input('site_ids', []));
            DB::commit();
            return true;
        } catch (Throwable $e) {
            DB::rollBack();
            Log::error($e);
            return false;
        }
    }
}
