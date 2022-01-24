<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\Seats\StoreRequest;
use App\Http\Requests\Admin\Seats\UpdateRequest;
use App\Models\Seat;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Throwable;

class SeatsController extends BaseController
{
    public function index(): View
    {
        $seats = Seat::orderBy('number', 'asc')->get();

        return view('admin.seats.index', [
            'seats' => $seats,
        ]);
    }

    public function create(Request $request): View
    {
        return view('admin.seats.create', [
            'seat' => new Seat(),
            'redirectUrl' => $this->redirectUrl($request),
        ]);
    }

    public function store(StoreRequest $request): RedirectResponse
    {
        $data = $request->validated();
        Seat::create($data);

        if ($redirect_url = $this->redirectUrl($request)) {
            return redirect($redirect_url)->with('success', 'Seat created');
        }

        return redirect()
            ->route('admin.seats.index')
            ->with('success', 'Seat created');
    }

    public function edit(Seat $seat, Request $request): View
    {
        return view('admin.seats.edit', [
            'seat' => $seat,
            'redirectUrl' => $this->redirectUrl($request),
        ]);
    }

    public function update(UpdateRequest $request, Seat $seat): RedirectResponse
    {
        $data = $request->validated();
        $seat->update($data);

        if ($redirect_url = $this->redirectUrl($request)) {
            return redirect($redirect_url)->with('success', 'Seat updated');
        }

        return redirect()
            ->route('admin.seats.index')
            ->with('success', 'Seat updated');
    }

    public function destroy(Seat $seat, Request $request): RedirectResponse
    {
        try {
            $seat->delete();

            if ($redirect_url = $this->redirectUrl($request)) {
                return redirect($redirect_url)->with('success', 'Seat deleted');
            }

            return redirect()
                ->route('admin.seats.index')
                ->with('success', 'Seat deleted');
        } catch (Throwable $e) {
            return redirect()->back()->with('warning', 'Failed to delete seat');
        }
    }
}
