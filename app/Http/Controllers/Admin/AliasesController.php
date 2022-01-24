<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\Aliases\StoreRequest;
use App\Http\Requests\Admin\Aliases\UpdateRequest;
use App\Models\Alias;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AliasesController extends BaseController
{
    public function index(Request $request): View
    {
        $aliases = Alias::with('page', 'site')->ransack($request->all())
            ->orderBy('capture_path', 'asc')->paginate(15);

        return view('admin.aliases.index', [
            'aliases' => $aliases,
            'sites' => $this->fetchSites(),
        ]);
    }

    public function create(Request $request): View
    {
        $alias = new Alias();

        return view('admin.aliases.create', [
            'alias' => $alias,
            'sites' => $this->fetchSites(),
            'redirectUrl' => $this->redirectUrl($request),
        ]);
    }

    public function store(StoreRequest $request): RedirectResponse
    {
        $data = $request->validated();

        Alias::create($data);

        if ($redirect_url = $this->redirectUrl($request)) {
            return redirect($redirect_url)->with('success', 'Alias created');
        }

        return redirect()->route('admin.aliases.index')
            ->with('success', 'Alias created');
    }

    public function edit(Alias $alias, Request $request): View
    {
        return view('admin.aliases.edit', [
            'alias' => $alias,
            'sites' => $this->fetchSites(),
            'redirectUrl' => $this->redirectUrl($request),
        ]);
    }


    public function update(UpdateRequest $request, Alias $alias): RedirectResponse
    {
        $data = $request->validated();

        $alias->update($data);

        if ($redirect_url = $this->redirectUrl($request)) {
            return redirect($redirect_url)->with('success', 'Alias updated');
        }

        return redirect()->route('admin.aliases.index')
            ->with('success', 'Alias updated');
    }

    public function destroy(Alias $alias, Request $request): RedirectResponse
    {
        $alias->delete();

        if ($redirect_url = $this->redirectUrl($request)) {
            return redirect($redirect_url)->with('success', 'Alias deleted');
        }

        return redirect()->route('admin.aliases.index')
            ->with('success', 'Alias deleted');
    }
}
