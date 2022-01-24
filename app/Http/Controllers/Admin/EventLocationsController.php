<?php

namespace App\Http\Controllers\Admin;

use App\Models\EventLocation;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use App\Http\Requests\Admin\EventLocations\StoreRequest;
use App\Http\Requests\Admin\EventLocations\UpdateRequest;
use Illuminate\Http\Request;

class EventLocationsController extends BaseController
{
    public function index(Request $request): View
    {
        $eventLocations = EventLocation::ransack($request->all())
            ->orderBy('name', 'asc')->get();

        return view('admin.event-locations.index', [
            'eventLocations' => $eventLocations,
        ]);
    }

    public function create(Request $request): View
    {
        return view('admin.event-locations.create', [
            'eventLocation' => new EventLocation(),
            'redirectUrl' => $this->redirectUrl($request),
        ]);
    }

    public function store(StoreRequest $request): RedirectResponse
    {
        EventLocation::create($request->validated());

        if ($redirect_url = $this->redirectUrl($request)) {
            return redirect($redirect_url)->with('success', 'Event location created');
        }

        return redirect()
            ->route('admin.event-locations.index')
            ->with('success', 'Event location created');
    }

    public function edit(EventLocation $eventLocation, Request $request): View
    {
        return view('admin.event-locations.edit', [
            'eventLocation' => $eventLocation,
            'redirectUrl' => $this->redirectUrl($request),
        ]);
    }

    public function update(UpdateRequest $request, EventLocation $eventLocation): RedirectResponse
    {
        $eventLocation->update($request->validated());

        if ($redirect_url = $this->redirectUrl($request)) {
            return redirect($redirect_url)->with('success', 'Event location updated');
        }

        return redirect()
            ->route('admin.event-locations.index')
            ->with('success', 'Event location updated');
    }

    public function destroy(EventLocation $eventLocation, Request $request): RedirectResponse
    {
        $eventLocation->delete();

        if ($redirect_url = $this->redirectUrl($request)) {
            return redirect($redirect_url)->with('success', 'Event location deleted');
        }

        return redirect()
            ->route('admin.event-locations.index')
            ->with('success', 'Event location deleted');
    }
}
