<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\Fieldsets\StoreRequest;
use App\Http\Requests\Admin\Fieldsets\UpdateRequest;
use App\Models\Fieldset;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class FieldsetsController extends BaseController
{
    public function index(Request $request): View
    {
        $fieldsets = Fieldset::withCount('fields')->ransack($request->all())->get();

        return view('admin.fieldsets.index', [
            'fieldsets' => $fieldsets,
        ]);
    }

    public function create(Request $request): View
    {
        $fieldset = new Fieldset();

        return view('admin.fieldsets.create', [
            'fieldset' => $fieldset,
            'redirectUrl' => $this->redirectUrl($request),
        ]);
    }

    public function store(StoreRequest $request): RedirectResponse
    {
        $data = $request->validated();

        Fieldset::create($data);

        if ($redirect_url = $this->redirectUrl($request)) {
            return redirect($redirect_url)->with('success', 'Fieldset created');
        }

        return redirect()
            ->route('admin.fieldsets.index')
            ->with('success', 'Fieldset created');
    }

    public function edit(Fieldset $fieldset, Request $request): View
    {
        return view('admin.fieldsets.edit', [
            'fieldset' => $fieldset,
            'redirectUrl' => $this->redirectUrl($request),
        ]);
    }

    public function update(UpdateRequest $request, Fieldset $fieldset): RedirectResponse
    {
        $data = $request->validated();

        $fieldset->update($data);

        if ($redirect_url = $this->redirectUrl($request)) {
            return redirect($redirect_url)->with('success', 'Fieldset updated');
        }

        return redirect()
            ->route('admin.fieldsets.index')
            ->with('success', 'Fieldset updated');
    }

    public function destroy(Fieldset $fieldset, Request $request): RedirectResponse
    {
        $fieldset->delete();

        if ($redirect_url = $this->redirectUrl($request)) {
            return redirect($redirect_url)->with('success', 'Fieldset deleted');
        }

        return redirect()
            ->route('admin.fieldsets.index')
            ->with('success', 'Fieldset deleted');
    }
}
