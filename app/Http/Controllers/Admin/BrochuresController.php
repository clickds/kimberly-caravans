<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Foundation\Http\FormRequest;
use App\Models\Brochure;
use App\Models\BrochureGroup;
use App\Models\Traits\ImageDeletable;
use Throwable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\Admin\Brochures\StoreRequest;
use App\Http\Requests\Admin\Brochures\UpdateRequest;
use App\Models\CaravanRange;
use App\Models\MotorhomeRange;
use App\Models\Page;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class BrochuresController extends BaseController
{
    use ImageDeletable;

    public function index(Request $request): View
    {
        $brochures = Brochure::with('site')->ransack($request->all());

        $brochures = $brochures->orderBy('created_at', 'desc')->paginate(10);

        return view('admin.brochures.index', [
            'brochures' => $brochures,
            'brochureGroups' => $this->fetchBrochureGroups(),
            'listingPages' => $this->getPagesWithTemplate(Page::TEMPLATE_BROCHURES_LISTING),
        ]);
    }

    public function create(Request $request): View
    {
        return view('admin.brochures.create', [
            'brochure' => new Brochure(),
            'brochureGroups' => $this->fetchBrochureGroups(),
            'caravanRanges' => CaravanRange::orderBy('name')->get(),
            'motorhomeRanges' => MotorhomeRange::orderBy('name')->get(),
            'sites' => $this->fetchSites(),
            'redirectUrl' => $this->redirectUrl($request),
        ]);
    }

    public function store(StoreRequest $request): RedirectResponse
    {
        try {
            DB::beginTransaction();
            $brochure = Brochure::create($request->validated());
            $this->addImages($request, $brochure, ['image', 'brochure_file']);
            $this->syncRanges($brochure, $request);
            DB::commit();
        } catch (Throwable $e) {
            DB::rollBack();
            Log::error($e);
            return back()->with('warning', 'Failed to create brochure');
        }

        if ($redirect_url = $this->redirectUrl($request)) {
            return redirect($redirect_url)->with('success', 'Brochure created');
        }

        return redirect()->route('admin.brochures.index')->with('success', 'Brochure created');
    }

    public function edit(Brochure $brochure, Request $request): View
    {
        return view('admin.brochures.edit', [
            'brochure' => $brochure,
            'brochureGroups' => $this->fetchBrochureGroups(),
            'caravanRanges' => CaravanRange::orderBy('name')->get(),
            'motorhomeRanges' => MotorhomeRange::orderBy('name')->get(),
            'sites' => $this->fetchSites(),
            'redirectUrl' => $this->redirectUrl($request),
        ]);
    }

    public function update(UpdateRequest $request, Brochure $brochure): RedirectResponse
    {
        try {
            DB::beginTransaction();
            $brochure->update($request->validated());
            $this->addImages($request, $brochure, ['image', 'brochure_file']);
            $this->syncRanges($brochure, $request);
            DB::commit();
        } catch (Throwable $e) {
            DB::rollBack();
            Log::error($e);
            return back()->with('warning', 'Error, please try again');
        }

        if ($redirect_url = $this->redirectUrl($request)) {
            return redirect($redirect_url)->with('success', 'Brochure updated');
        }

        return redirect()
            ->route('admin.brochures.index')
            ->with('success', 'Brochure updated');
    }

    public function destroy(Brochure $brochure, Request $request): RedirectResponse
    {
        $brochure->delete();

        if ($redirect_url = $this->redirectUrl($request)) {
            return redirect($redirect_url)->with('success', 'Brochure deleted');
        }

        return redirect()
            ->route('admin.brochures.index')
            ->with('success', 'Brochure deleted');
    }

    public function fetchBrochureGroups(): Collection
    {
        return BrochureGroup::all();
    }

    private function addImages(FormRequest $request, Brochure $brochure, array $fileGroups): void
    {
        foreach ($fileGroups as $fileGroup) {
            if ($request->exists($fileGroup)) {
                $brochure->addMediaFromRequest($fileGroup)->toMediaCollection($fileGroup);
            }
        }
    }

    private function syncRanges(Brochure $brochure, Request $request): void
    {
        $brochure->caravanRanges()->sync(
            $request->get('caravan_range_ids', [])
        );

        $brochure->motorhomeRanges()->sync(
            $request->get('motorhome_range_ids', [])
        );
    }
}
