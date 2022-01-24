<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\Berths\StoreRequest;
use App\Http\Requests\Admin\Berths\UpdateRequest;
use App\Models\Berth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class BerthsController extends BaseController
{
    public function index(): View
    {
        $berths = Berth::orderBy('number', 'asc')->get();

        return view('admin.berths.index', [
            'berths' => $berths,
        ]);
    }

    public function create(Request $request): View
    {
        $berth = new Berth();

        return view('admin.berths.create', [
            'berth' => $berth,
            'redirectUrl' => $this->redirectUrl($request),
        ]);
    }

    public function store(StoreRequest $request): RedirectResponse
    {
        $data = $request->validated();
        Berth::create($data);

        if ($redirect_url = $this->redirectUrl($request)) {
            return redirect($redirect_url)->with('success', 'Berth created');
        }

        return redirect()
            ->route('admin.berths.index')
            ->with('success', 'Berth created');
    }

    public function edit(Berth $berth, Request $request): View
    {
        return view('admin.berths.edit', [
            'berth' => $berth,
            'redirectUrl' => $this->redirectUrl($request),
        ]);
    }

    public function update(UpdateRequest $request, Berth $berth): RedirectResponse
    {
        $data = $request->validated();
        $berth->update($data);

        if ($redirect_url = $this->redirectUrl($request)) {
            return redirect($redirect_url)->with('success', 'Berth updated');
        }

        return redirect()
            ->route('admin.berths.index')
            ->with('success', 'Berth updated');
    }

    public function destroy(Berth $berth, Request $request): RedirectResponse
    {
        try {
            $berth->delete();

            if ($redirect_url = $this->redirectUrl($request)) {
                return redirect($redirect_url)->with('success', 'Berth deleted');
            }

            return redirect()
                ->route('admin.berths.index')
                ->with('success', 'Berth deleted');
        } catch (Throwable $e) {
            Log::error($e);

            return redirect()
                ->back()
                ->with('warning', 'Failed to delete berth');
        }
    }
}
