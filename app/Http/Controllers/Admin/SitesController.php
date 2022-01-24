<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Sites\StoreRequest;
use App\Http\Requests\Admin\Sites\UpdateRequest;
use App\Models\Site;
use App\Services\Site\FlagFinder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SitesController extends BaseController
{
    public function index(): View
    {
        $sites = Site::withCount('openingTimes')->orderBy('country', 'asc')->get();

        return view('admin.sites.index', [
            'sites' => $sites,
        ]);
    }

    public function create(Request $request): View
    {
        return view('admin.sites.create', [
            'site' => new Site(),
            'flags' => $this->fetchFlags(),
            'redirectUrl' => $this->redirectUrl($request),
        ]);
    }

    public function store(StoreRequest $request): RedirectResponse
    {
        Site::create($request->validated());

        if ($redirect_url = $this->redirectUrl($request)) {
            return redirect($redirect_url)->with('success', 'Site created');
        }

        return redirect()
            ->route('admin.sites.index')
            ->with('success', 'Site created');
    }

    public function edit(Site $site, Request $request): View
    {
        return view('admin.sites.edit', [
            'site' => $site,
            'flags' => $this->fetchFlags(),
            'redirectUrl' => $this->redirectUrl($request),
        ]);
    }

    public function update(UpdateRequest $request, Site $site): RedirectResponse
    {
        $site->update($request->validated());

        if ($redirect_url = $this->redirectUrl($request)) {
            return redirect($redirect_url)->with('success', 'Site updated');
        }

        return redirect()
            ->route('admin.sites.index')
            ->with('success', 'Site updated');
    }

    public function destroy(Site $site, Request $request): RedirectResponse
    {
        $site->delete();

        if ($redirect_url = $this->redirectUrl($request)) {
            return redirect($redirect_url)->with('success', 'Site deleted');
        }

        return redirect()
            ->route('admin.sites.index')
            ->with('success', 'Site deleted');
    }

    private function fetchFlags(): array
    {
        $finder = new FlagFinder();
        return $finder->call();
    }
}
