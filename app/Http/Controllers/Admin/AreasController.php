<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\Areas\CreateAreaRequest;
use App\Http\Requests\Admin\Areas\UpdateAreaRequest;
use App\Models\Area;
use App\Models\Page;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AreasController extends BaseController
{
    public function index(Page $page): View
    {
        return view('admin.areas.index', [
            'page' => $page,
            'areas' => $page->areas()->orderBy('position', 'asc')->get(),
        ]);
    }

    public function create(Page $page, Request $request): View
    {
        $area = new Area();

        return view('admin.areas.create', [
            'area' => $area,
            'page' => $page,
            'backgroundColours' => $this->fetchBackgroundColours(),
            'columns' => $this->fetchColumns(),
            'widths' => $this->fetchWidths(),
            'holders' => $page->availableHolders(),
            'headingTypes' => $this->fetchHeadingTypes(),
            'textAlignments' => $this->fetchTextAlignments(),
            'redirectUrl' => $this->redirectUrl($request),
        ]);
    }

    public function edit(Page $page, Area $area, Request $request): View
    {
        return view('admin.areas.edit', [
            'area' => $area,
            'page' => $page,
            'backgroundColours' => $this->fetchBackgroundColours(),
            'columns' => $this->fetchColumns(),
            'widths' => $this->fetchWidths(),
            'holders' => $page->availableHolders(),
            'headingTypes' => $this->fetchHeadingTypes(),
            'textAlignments' => $this->fetchTextAlignments(),
            'redirectUrl' => $this->redirectUrl($request),
        ]);
    }

    public function store(Page $page, CreateAreaRequest $request): RedirectResponse
    {
        $areaData = $request->validated();
        $area = $page->areas()->create($areaData);

        return $this->calculateSuccessfulRedirect($area, $request);
    }

    public function update(Page $page, Area $area, UpdateAreaRequest $request): RedirectResponse
    {
        $area->update($request->validated());

        return $this->calculateSuccessfulRedirect($area, $request);
    }

    public function destroy(Page $page, Area $area, Request $request): RedirectResponse
    {
        $area->delete();

        if ($redirect_url = $this->redirectUrl($request)) {
            return redirect($redirect_url)->with('success', 'Area deleted');
        }

        return redirect()->route('admin.pages.areas.index', $page->id);
    }

    private function calculateSuccessfulRedirect(Area $area, FormRequest $request): RedirectResponse
    {
        if ($redirect_url = $this->redirectUrl($request)) {
            return redirect($redirect_url)->with('success', 'Area has been saved');
        }

        return redirect()->route('admin.pages.areas.index', $area->page)
            ->with('success', 'Area has been saved');
    }

    private function fetchBackgroundColours(): array
    {
        return Area::BACKGROUND_COLOURS;
    }

    private function fetchColumns(): array
    {
        return Area::COLUMNS;
    }

    private function fetchHeadingTypes(): array
    {
        return Area::HEADING_TYPES;
    }

    private function fetchTextAlignments(): array
    {
        return Area::TEXT_ALIGNMENTS;
    }

    private function fetchWidths(): array
    {
        return Area::WIDTHS;
    }
}
