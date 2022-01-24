<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\Admin\EventResource;
use App\Models\Event;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class EventsController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        $events = Event::orderBy('name', 'asc')->get();

        return EventResource::collection($events);
    }
}
