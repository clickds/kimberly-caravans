<?php

namespace Tests\Unit\Services\Pageable;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Event;
use App\Models\Page;
use App\Services\Pageable\EventPageSaver;

class EventPageSaverTest extends TestCase
{
    use RefreshDatabase;

    public function test_when_event_pageable_creates_event_show_page()
    {
        $site = $this->createSite();
        $event = factory(Event::class)->create();
        $saver = new EventPageSaver($event, $site);

        $saver->call();

        $this->assertDatabaseHas('pages', [
            "site_id" => $site->id,
            "name" => $event->name,
            "pageable_type" => Event::class,
            "pageable_id" => $event->id,
            "template" => Page::TEMPLATE_EVENT_SHOW,
        ]);
    }
}
