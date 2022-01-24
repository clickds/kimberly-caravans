<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\Events\StoreRequest;
use App\Http\Requests\Admin\Events\UpdateRequest;
use App\Models\Dealer;
use App\Models\EventLocation;
use Illuminate\Database\Eloquent\Collection;
use App\Models\Event;
use App\Models\Page;
use App\Models\Site;
use App\Services\Pageable\EventPageSaver;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class EventsController extends BaseController
{
    public function index(Request $request): View
    {
        $events = Event::with('pages')->ransack($request->all())
            ->orderBy('start_date', 'desc')->paginate(15);

        return view('admin.events.index', [
            'events' => $events,
            'locations' => $this->getLocations(),
            'dealers' => $this->getDealers(),
            'listingPages' => $this->getPagesWithTemplate(Page::TEMPLATE_EVENTS_LISTING),
        ]);
    }

    public function create(Request $request): View
    {
        $event = new Event([
            'start_date' => now(),
            'end_date' => now(),
        ]);

        return view('admin.events.create', [
            'event' => $event,
            'locations' => $this->getLocations(),
            'dealers' => $this->getDealers(),
            'redirectUrl' => $this->redirectUrl($request),
        ]);
    }

    public function store(StoreRequest $request): RedirectResponse
    {
        try {
            DB::beginTransaction();

            $event = Event::create($request->validated());

            $this->updateDefaultSitePage($event);

            $this->addImageToEvent($event, $request);

            DB::commit();
        } catch (Throwable $e) {
            Log::error($e);

            DB::rollBack();

            return redirect()
                ->back()
                ->withInput($request->all())
                ->with('error', 'Failed to create event');
        }

        if ($redirect_url = $this->redirectUrl($request)) {
            return redirect($redirect_url)->with('success', 'Event created');
        }

        return redirect()->route('admin.events.index')->with('success', 'Event created');
    }

    public function edit(Event $event, Request $request): View
    {
        return view('admin.events.edit', [
            'event' => $event,
            'locations' => $this->getLocations(),
            'dealers' => $this->getDealers(),
            'redirectUrl' => $this->redirectUrl($request),
        ]);
    }

    public function update(UpdateRequest $request, Event $event): RedirectResponse
    {
        try {
            DB::beginTransaction();

            $event->update($request->validated());

            $this->updateDefaultSitePage($event);

            $this->addImageToEvent($event, $request);

            DB::commit();
        } catch (Throwable $e) {
            Log::error($e);

            DB::rollBack();

            return redirect()
                ->back()
                ->withInput($request->all())
                ->with('error', 'Failed to update event');
        }

        if ($redirect_url = $this->redirectUrl($request)) {
            return redirect($redirect_url)->with('success', 'Event updated');
        }

        return redirect()->route('admin.events.index')->with('success', 'Event updated');
    }

    public function destroy(Event $event, Request $request): RedirectResponse
    {
        $event->delete();

        if ($redirect_url = $this->redirectUrl($request)) {
            return redirect($redirect_url)->with('success', 'Event deleted');
        }

        return redirect()
            ->route('admin.events.index')
            ->with('success', 'Event deleted');
    }

    private function getDealers(): Collection
    {
        return Dealer::select('id', 'name')->orderBy('name', 'asc')->get();
    }

    private function getLocations(): Collection
    {
        return EventLocation::select('id', 'name')->orderBy('name', 'asc')->get();
    }

    private function updateDefaultSitePage(Event $event): void
    {
        $defaultSite = Site::where('is_default', true)->firstOrFail();

        $event->sites()->sync([$defaultSite->id]);

        $saver = new EventPageSaver($event, $defaultSite);

        $saver->call();
    }

    private function addImageToEvent(Event $event, FormRequest $request): void
    {
        if (!$request->hasFile('image')) {
            return;
        }

        $event->addMediaFromRequest('image')->toMediaCollection('image');
    }
}
