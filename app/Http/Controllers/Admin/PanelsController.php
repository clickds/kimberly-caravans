<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\Panels\CreatePanelRequest;
use App\Http\Requests\Admin\Panels\UpdatePanelRequest;
use App\Models\Area;
use App\Models\Panel;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class PanelsController extends BaseController
{
    public function index(Area $area): View
    {
        return view('admin.panels.index', [
            'area' => $area,
            'panels' => $area->panels()->orderBy('position', 'asc')->get()
        ]);
    }

    public function create(Area $area, Request $request): View
    {
        $panel = $area->panels()->make();

        return view('admin.panels.create', [
            'areas' => $this->fetchAreas($area),
            'area' => $area,
            'panel' => $panel,
            'siteId' => $area->page->site_id,
            'headingTypes' => $this->fetchHeadingTypes(),
            'types' => $this->fetchTypes(),
            'textAlignments' => $this->fetchTextAlignments(),
            'overlayPositions' => $this->fetchOverlayPositions(),
            'verticalPositions' => $this->fetchVerticalPositions(),
            'vehicleTypes' => $this->fetchVehicleTypes(),
            'redirectUrl' => $this->redirectUrl($request),
        ]);
    }

    public function store(Area $area, CreatePanelRequest $request): RedirectResponse
    {
        $panel = $area->panels()->make();
        if ($this->savePanel($panel, $request)) {
            return $this->calculateSuccessfulRedirect($panel, $request);
        }

        return back()
            ->withInput($request->all())
            ->with('warning', 'Failed to create panel');
    }


    public function edit(Area $area, Panel $panel, Request $request): View
    {
        return view('admin.panels.edit', [
            'areas' => $this->fetchAreas($area),
            'area' => $area,
            'panel' => $panel,
            'siteId' => $area->page->site_id,
            'headingTypes' => $this->fetchHeadingTypes(),
            'textAlignments' => $this->fetchTextAlignments(),
            'types' => $this->fetchTypes(),
            'overlayPositions' => $this->fetchOverlayPositions(),
            'verticalPositions' => $this->fetchVerticalPositions(),
            'vehicleTypes' => $this->fetchVehicleTypes(),
            'redirectUrl' => $this->redirectUrl($request),
        ]);
    }

    public function update(UpdatePanelRequest $request, Area $area, Panel $panel): RedirectResponse
    {
        if ($this->savePanel($panel, $request)) {
            return $this->calculateSuccessfulRedirect($panel, $request);
        }

        return back()
            ->withInput($request->all())
            ->with('warning', 'Failed to update panel');
    }

    public function destroy(Area $area, Panel $panel, Request $request): RedirectResponse
    {
        $panel->delete();

        if ($redirect_url = $this->redirectUrl($request)) {
            return redirect($redirect_url)->with('success', 'Panel deleted');
        }

        return redirect()
            ->route('admin.areas.panels.index', $area)
            ->with('success', 'Panel deleted');
    }

    private function savePanel(Panel $panel, FormRequest $request): bool
    {
        DB::beginTransaction();
        try {
            $mediaItems = $request->allFiles();

            $nonPanelInputKeys = array_keys($mediaItems);
            $nonPanelInputKeys[] = 'special_offer_ids';

            $panelData = Arr::except($request->validated(), $nonPanelInputKeys);
            $panel->fill($panelData);
            $panel->save();

            if (!empty($mediaItems)) {
                $this->addMediaItemsToPanel($panel, $mediaItems);
            } else {
                $this->updateMediaItemsForPanel($panel);
            }

            $specialOfferIds = $request->get('special_offer_ids', []);
            $panel->specialOffers()->sync($specialOfferIds);
            DB::commit();

            return true;
        } catch (Throwable $e) {
            DB::rollBack();
            Log::error($e);
            return false;
        }
    }

    private function calculateSuccessfulRedirect(Panel $panel, FormRequest $request): RedirectResponse
    {
        if ($redirect_url = $this->redirectUrl($request)) {
            return redirect($redirect_url)->with('success', 'Panel has been saved');
        }

        return redirect()->route('admin.areas.panels.index', $panel->area)->with('success', 'Panel has been saved');
    }

    private function addMediaItemsToPanel(Panel $panel, array $mediaItems): void
    {
        foreach ($mediaItems as $inputKey => $file) {
            $collectionName = $this->collectionNameFromInputKey($inputKey);

            $panel->addMedia($file)
                ->usingName($panel->getAltTextForImagePanelTypes())
                ->toMediaCollection($collectionName);
        }
    }

    private function updateMediaItemsForPanel(Panel $panel): void
    {
        switch ($panel->type) {
            case Panel::TYPE_FEATURED_IMAGE:
                $this->updateImageAltForCollection($panel, 'featuredImage');
                break;
            case Panel::TYPE_IMAGE:
                $this->updateImageAltForCollection($panel, 'image');
                break;
            default:
                return;
        }
    }

    private function updateImageAltForCollection(Panel $panel, string $collectionName): void
    {
        $media = $panel->getFirstMedia($collectionName);

        if (is_null($media)) {
            return;
        }

        $media->name = $panel->getAltTextForImagePanelTypes();
        $media->save();
    }

    /**
     * @param int|string $inputKey
     */
    private function collectionNameFromInputKey($inputKey): string
    {
        switch ($inputKey) {
            case 'featured_image':
                return 'featuredImage';
            default:
                return (string) $inputKey;
        }
    }

    private function fetchHeadingTypes(): array
    {
        return Panel::HEADING_TYPES;
    }

    private function fetchTextAlignments(): array
    {
        return Panel::TEXT_ALIGNMENTS;
    }

    private function fetchTypes(): array
    {
        return Panel::TYPES;
    }

    private function fetchOverlayPositions(): array
    {
        return Panel::OVERLAY_POSITIONS;
    }

    private function fetchVerticalPositions(): array
    {
        return Panel::VERTICAL_POSITIONS;
    }

    private function fetchVehicleTypes(): array
    {
        return Panel::VEHICLE_TYPES;
    }

    private function fetchAreas(Area $area): Collection
    {
        return Area::where('page_id', $area->page_id)->orderBy('name', 'asc')->get();
    }
}
