<?php

namespace App\Presenters\Cta;

use App\Models\Event;
use App\Models\Page;
use App\Presenters\Page\BasePagePresenter;
use Carbon\Carbon;

class EventTypePresenter extends BasePresenter
{
    private ?Page $nextEventPage;

    public function __construct()
    {
        $this->nextEventPage = $this->fetchNextEventPage();
    }

    public function partialPath(): string
    {
        return 'ctas._event';
    }

    public function page(): BasePagePresenter
    {
        $page = $this->getNextEventPage();
        if (is_null($page)) {
            $page = new Page();
        }
        return (new BasePagePresenter())->setWrappedObject($page);
    }

    public function displayable(): bool
    {
        if (is_null($this->nextEventPage)) {
            return false;
        }
        return true;
    }

    public function eventName(): string
    {
        $event = $this->getEvent();
        if (is_null($event)) {
            return "";
        }
        return $event->name;
    }

    public function eventDates(): string
    {
        $event = $this->getEvent();
        if (is_null($event)) {
            return "";
        }
        $dates = [];
        $dates[] = $this->parseDate($event->start_date);
        $dates[] = $this->parseDate($event->end_date);

        $dates = array_filter($dates);
        $dates = array_unique($dates);

        return implode(' - ', $dates);
    }

    private function getNextEventPage(): ?Page
    {
        return $this->nextEventPage;
    }

    private function getEvent(): ?Event
    {
        $eventPage = $this->getNextEventPage();
        if (is_null($eventPage)) {
            return null;
        }
        $event = $eventPage->pageable;
        if (is_null($event) || get_class($event) != Event::class) {
            return null;
        }
        return $event;
    }

    private function fetchNextEventPage(): ?Page
    {
        $eventId = $this->nextEventId();
        if (is_null($eventId)) {
            return null;
        }
        return Page::with('pageable', 'parent:id,slug')
            ->where('template', Page::TEMPLATE_EVENT_SHOW)
            ->where('pageable_type', Event::class)
            ->where('pageable_id', $eventId)
            ->displayable()
            ->first();
    }

    private function nextEventId(): ?int
    {
        return Event::upcoming()->toBase()->value('id');
    }

    private function parseDate(?Carbon $date): ?string
    {
        if (is_null($date)) {
            return null;
        }
        return $date->format('d F Y');
    }
}
