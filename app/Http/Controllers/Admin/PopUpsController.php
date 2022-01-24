<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\PopUps\StoreRequest;
use App\Http\Requests\Admin\PopUps\UpdateRequest;
use App\Models\CaravanRange;
use App\Models\MotorhomeRange;
use App\Models\PopUp;
use App\Models\Site;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class PopUpsController extends BaseController
{
    public function index(Request $request): View
    {
        $popUps = PopUp::ransack($request->all())->orderBy('created_at', 'desc')->paginate();

        return view('admin.pop-ups.index', [
            'popUps' => $popUps,
        ]);
    }

    public function create(Request $request): View
    {
        return view('admin.pop-ups.create', [
            'popUp' => new PopUp(),
            'sites' => $this->getSites(),
            'caravanRangeIds' => [],
            'motorhomeRangeIds' => [],
            'appearsOnPageIds' => [],
            'caravanRanges' => $this->fetchCaravanRanges(),
            'motorhomeRanges' => $this->fetchMotorhomeRanges(),
            'redirectUrl' => $this->redirectUrl($request),
        ]);
    }

    public function store(StoreRequest $request): RedirectResponse
    {
        $popUp = new PopUp();

        if ($this->savePopup($popUp, $request)) {
            if ($redirect_url = $this->redirectUrl($request)) {
                return redirect($redirect_url)->with('success', 'Pop up created');
            }

            return redirect()
                ->route('admin.pop-ups.index')
                ->with('success', 'Pop up created');
        }
        return redirect()
            ->back()
            ->withInput($request->all())
            ->with('warning', 'Failed to create pop up');
    }

    public function edit(PopUp $popUp, Request $request): View
    {
        return view('admin.pop-ups.edit', [
            'popUp' => $popUp,
            'sites' => $this->getSites(),
            'caravanRangeIds' => $popUp->caravanRanges()->pluck('id')->toArray(),
            'motorhomeRangeIds' => $popUp->motorhomeRanges()->pluck('id')->toArray(),
            'appearsOnPageIds' => $popUp->appearsOnPages()->pluck('id')->toArray(),
            'caravanRanges' => $this->fetchCaravanRanges(),
            'motorhomeRanges' => $this->fetchMotorhomeRanges(),
            'redirectUrl' => $this->redirectUrl($request),
        ]);
    }

    public function update(UpdateRequest $request, PopUp $popUp): RedirectResponse
    {
        if ($this->savePopup($popUp, $request)) {
            if ($redirect_url = $this->redirectUrl($request)) {
                return redirect($redirect_url)->with('success', 'Pop up updated');
            }

            return redirect()
                ->route('admin.pop-ups.index')
                ->with('success', 'Pop up updated');
        }

        return redirect()
            ->back()
            ->withInput($request->all())
            ->with('warning', 'Failed to update pop up');
    }

    public function destroy(PopUp $popUp, Request $request): RedirectResponse
    {
        $popUp->delete();

        if ($redirect_url = $this->redirectUrl($request)) {
            return redirect($redirect_url)->with('success', 'Pop up deleted');
        }

        return redirect()
            ->route('admin.pop-ups.index')
            ->with('success', 'Pop up deleted');
    }

    private function savePopup(PopUp $popUp, FormRequest $request): bool
    {
        $data = $request->validated();
        DB::beginTransaction();
        try {
            $popUp->fill($data);
            $popUp->save();
            $popUp->appearsOnPages()->sync($request->input('appears_on_page_ids', []));
            $popUp->caravanRanges()->sync($request->input('caravan_range_ids', []));
            $popUp->motorhomeRanges()->sync($request->input('motorhome_range_ids', []));
            $this->attachImages($popUp, $request);
            DB::commit();
            return true;
        } catch (Throwable $e) {
            DB::rollBack();
            Log::error($e);
            return false;
        }
    }

    private function getSites(): Collection
    {
        return Site::orderBy('country', 'asc')->select('country', 'id')->get();
    }

    private function attachImages(PopUp $popUp, Request $request): void
    {
        $images = [
            'mobile_image' => 'mobileImage',
            'desktop_image' => 'desktopImage',
        ];

        foreach ($images as $inputName => $collection) {
            if ($request->hasFile($inputName)) {
                $popUp->addMediaFromRequest($inputName)->toMediaCollection($collection);
            }
        }
    }

    private function fetchCaravanRanges(): Collection
    {
        return CaravanRange::with('manufacturer:id,name')->orderBy('name', 'asc')
            ->select('name', 'id', 'manufacturer_id')->get();
    }

    private function fetchMotorhomeRanges(): Collection
    {
        return MotorhomeRange::with('manufacturer:id,name')->orderBy('name', 'asc')
            ->select('name', 'id', 'manufacturer_id')->get();
    }
}
