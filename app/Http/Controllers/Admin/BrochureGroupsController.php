<?php

namespace App\Http\Controllers\Admin;

use App\Models\BrochureGroup;
use Illuminate\Http\Request;
use App\Http\Requests\Admin\BrochureGroups\StoreRequest;
use App\Http\Requests\Admin\BrochureGroups\UpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class BrochureGroupsController extends BaseController
{
    public function index(Request $request): View
    {
        $brochure_groups = BrochureGroup::ransack($request->all())
            ->orderBy('position', 'asc')->paginate(10);

        return view('admin.brochure-groups.index', [
            'brochure_groups' => $brochure_groups,
        ]);
    }

    public function create(Request $request): View
    {
        return view('admin.brochure-groups.create', [
            'brochure_group' => new BrochureGroup(),
            'redirectUrl' => $this->redirectUrl($request),
        ]);
    }

    public function store(StoreRequest $request): RedirectResponse
    {
        $data = $request->validated();
        BrochureGroup::create($data);

        if ($redirect_url = $this->redirectUrl($request)) {
            return redirect($redirect_url)->with('success', 'Brochure group created');
        }

        return redirect()
            ->route('admin.brochure-groups.index')
            ->with('success', 'Brochure group created');
    }

    public function edit(BrochureGroup $brochure_group, Request $request): View
    {
        return view('admin.brochure-groups.edit', [
            'brochure_group' => $brochure_group,
            'redirectUrl' => $this->redirectUrl($request),
        ]);
    }

    public function update(UpdateRequest $request, BrochureGroup $brochure_group): RedirectResponse
    {
        $data = $request->validated();
        $brochure_group->update($data);

        if ($redirect_url = $this->redirectUrl($request)) {
            return redirect($redirect_url)->with('success', 'Brochure group updated');
        }

        return redirect()
            ->route('admin.brochure-groups.index')
            ->with('success', 'Brochure group updated');
    }

    public function destroy(BrochureGroup $brochure_group, Request $request): RedirectResponse
    {
        $brochure_group->delete();

        if ($redirect_url = $this->redirectUrl($request)) {
            return redirect($redirect_url)->with('success', 'Brochure group deleted');
        }

        return redirect()->route('admin.brochure-groups.index')->with('success', 'Brochure group deleted');
    }
}
