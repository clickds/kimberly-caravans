<?php

namespace Tests\Feature\Pages;

use App\Models\EventLocation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Event;
use App\Models\Page;
use App\Models\Site;
use App\Facades\Event\ShowPage;

class EventPageTest extends TestCase
{
    use RefreshDatabase;

    public function test_rendered_correctly()
    {
        $site = factory(Site::class)->states(['has-stock', 'default'])->create();
        $parentPage = factory(Page::class)->create([
            'site_id' => $site->id,
            'template' => Page::TEMPLATE_EVENTS_LISTING,
        ]);

        $eventLocation = factory(EventLocation::class)->create();
        $event = factory(Event::class)->create();
        $event->eventLocation()->associate($eventLocation)->save();

        $page = $event->pages()->create([
            'parent_id' => $parentPage->id,
            'site_id' => $site->id,
            'template' => Page::TEMPLATE_EVENT_SHOW,
            'name' => $event->name,
            'meta_title' => $event->name,
        ]);

        $this->withoutExceptionHandling();
        $response = $this->get($parentPage->slug . '/' . $page->slug);

        $response->assertStatus(200);

        $facade = $response->viewData('pageFacade');
        $this->assertInstanceOf(ShowPage::class, $facade);
        $response->assertSee($event->name);
    }
}
