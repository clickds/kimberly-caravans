<?php

namespace App\Http\Controllers\Admin\MotorhomeRange;

use App\Http\Controllers\Admin\BaseController;
use App\Http\Requests\Admin\MotorhomeRange\RangeFeatures\StoreRequest;
use App\Http\Requests\Admin\MotorhomeRange\RangeFeatures\UpdateRequest;
use App\Models\MotorhomeRange;
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
    public function index(Request $request, MotorhomeRange $motorhomeRange): View
    {
        $rangeFeatures = $motorhomeRange->features()
            ->ransack($request->all())
            ->orderBy('position', 'asc')->get();

        return view('admin.motorhome-range.range-features.index', [
            'motorhomeRange' => $motorhomeRange,
            'rangeFeatures' => $rangeFeatures,
        ]);
    }

    public function create(MotorhomeRange $motorhomeRange, Request $request): View
    {
        $rangeFeature = $motorhomeRange->features()->make();

        return view('admin.motorhome-range.range-features.create', [
            'motorhomeRange' => $motorhomeRange,
            'rangeFeature' => $rangeFeature,
            'sites' => $this->fetchSites(),
            'redirectUrl' => $this->redirectUrl($request),
        ]);
    }

    public function store(StoreRequest $request, MotorhomeRange $motorhomeRange): RedirectResponse
    {
        $rangeFeature = $motorhomeRange->features()->make();

        if ($this->saveRangeFeature($rangeFeature, $request)) {
            if ($redirect_url = $this->redirectUrl($request)) {
                return redirect($redirect_url)->with('success', 'Feature created');
            }

            return redirect()
                ->route('admin.motorhome-ranges.range-features.index', $motorhomeRange)
                ->with('success', 'Feature created');
        }

        return redirect()
            ->back()
            ->withInput($request->all())
            ->with('warning', 'Failed to create feature');
    }

    public function edit(MotorhomeRange $motorhomeRange, RangeFeature $rangeFeature, Request $request): View
    {
        return view('admin.motorhome-range.range-features.edit', [
            'motorhomeRange' => $motorhomeRange,
            'rangeFeature' => $rangeFeature,
            'sites' => $this->fetchSites(),
            'redirectUrl' => $this->redirectUrl($request),
        ]);
    }

    public function update(
        UpdateRequest $request,
        MotorhomeRange $motorhomeRange,
        RangeFeature $rangeFeature
    ): RedirectResponse {
        if ($this->saveRangeFeature($rangeFeature, $request)) {
            if ($redirect_url = $this->redirectUrl($request)) {
                return redirect($redirect_url)->with('success', 'Feature updated');
            }

            return redirect()
                ->route('admin.motorhome-ranges.range-features.index', $motorhomeRange)
                ->with('success', 'Feature updated');
        }

        return redirect()
            ->back()
            ->withInput($request->all())
            ->with('error', 'Failed to update feature');
    }

    public function destroy(
        MotorhomeRange $motorhomeRange,
        RangeFeature $rangeFeature,
        Request $request
    ): RedirectResponse {
        $rangeFeature->delete();

        if ($redirect_url = $this->redirectUrl($request)) {
            return redirect($redirect_url)->with('success', 'Feature deleted');
        }

        return redirect()
            ->route('admin.motorhome-ranges.range-features.index', $motorhomeRange)
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
