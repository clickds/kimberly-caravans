<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\BedDescriptions\StoreRequest;
use App\Http\Requests\Admin\BedDescriptions\UpdateRequest;
use App\Models\BedDescription;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class BedDescriptionsController extends BaseController
{
    public function index(Request $request): View
    {
        $bedDescriptions = BedDescription::ransack($request->all())->orderBy('name', 'asc')
            ->paginate(20);

        return view('admin.bed-descriptions.index', [
            'bedDescriptions' => $bedDescriptions,
        ]);
    }

    public function create(Request $request): View
    {
        $bedDescription = new BedDescription();

        return view('admin.bed-descriptions.create', [
            'bedDescription' => $bedDescription,
            'redirectUrl' => $this->redirectUrl($request),
        ]);
    }

    public function store(StoreRequest $request): RedirectResponse
    {
        $data = $request->validated();
        BedDescription::create($data);

        if ($redirect_url = $this->redirectUrl($request)) {
            return redirect($redirect_url)->with('success', 'Bed description created');
        }

        return redirect()
            ->route('admin.bed-descriptions.index')
            ->with('success', 'Bed description created');
    }

    public function edit(BedDescription $bedDescription, Request $request): View
    {
        return view('admin.bed-descriptions.edit', [
            'bedDescription' => $bedDescription,
            'redirectUrl' => $this->redirectUrl($request),
        ]);
    }

    public function update(UpdateRequest $request, BedDescription $bedDescription): RedirectResponse
    {
        $data = $request->validated();
        $bedDescription->update($data);

        if ($redirect_url = $this->redirectUrl($request)) {
            return redirect($redirect_url)->with('success', 'Bed description updated');
        }

        return redirect()
            ->route('admin.bed-descriptions.index')
            ->with('success', 'Bed description updated');
    }

    public function destroy(BedDescription $bedDescription, Request $request): RedirectResponse
    {
        try {
            $bedDescription->delete();

            if ($redirect_url = $this->redirectUrl($request)) {
                return redirect($redirect_url)->with('success', 'Bed description deleted');
            }

            return redirect()
                ->route('admin.bed-descriptions.index')
                ->with('success', 'Bed description deleted');
        } catch (QueryException $e) {
            return redirect()
                ->route('admin.bed-descriptions.index')
                ->with('warning', 'Cannot delete bed description that has bed sizes attached');
        }
    }
}
