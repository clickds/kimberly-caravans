<?php

namespace App\Facades\Event;

use App\Facades\BasePage;
use App\Models\Event;
use App\Models\Page;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Http\Request;

class ListingPage extends BasePage
{
    private Paginator $events;

    public function __construct(Page $page, Request $request)
    {
        parent::__construct($page, $request);

        $this->events = $this->fetchEvents();
    }

    public function getEvents(): Paginator
    {
        return $this->events;
    }

    private function fetchEvents(): Paginator
    {
        $request = $this->getRequest();
        return Event::with('dealer', 'eventLocation')
            ->orderBy('start_date', 'desc')->paginate($this->perPage());
    }
}
