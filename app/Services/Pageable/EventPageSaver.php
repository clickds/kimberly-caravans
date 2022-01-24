<?php

namespace App\Services\Pageable;

use Illuminate\Support\Facades\DB;
use Throwable;
use App\Models\Event;
use App\Models\Page;
use App\Models\Site;
use Illuminate\Support\Facades\Log;

class EventPageSaver
{
    /**
     * @var \App\Models\Site
     */
    private $site;
    /**
     * @var \App\Models\Event
     */
    private $event;

    public function __construct(Event $event, Site $site)
    {
        $this->event = $event;
        $this->site = $site;
    }

    public function call(): void
    {
        try {
            DB::beginTransaction();
            $this->saveEventPage();
            DB::commit();
        } catch (Throwable $e) {
            Log::error($e);
            DB::rollBack();
        }
    }

    private function saveEventPage(): void
    {
        $page = $this->findOrInitializeEventPage();
        $page->name = $this->getEvent()->name;
        $page->meta_title = $this->getEvent()->name;
        $page->parent_id = $this->findOrCreateEventListingsPage()->id;
        $page->save();
    }

    private function findOrInitializeEventPage(): Page
    {
        return $this->getEvent()->pages()->firstOrNew([
            'site_id' => $this->getSite()->id,
            'template' => Page::TEMPLATE_EVENT_SHOW,
        ]);
    }

    private function findOrCreateEventListingsPage(): Page
    {
        return Page::firstOrCreate(
            [
                'site_id' => $this->getSite()->id,
                'template' => Page::TEMPLATE_EVENTS_LISTING,
            ],
            [
                'name' => 'Events',
                'meta_title' => 'Events',
            ]
        );
    }

    private function getEvent(): Event
    {
        return $this->event;
    }

    private function getSite(): Site
    {
        return $this->site;
    }
}
