<?php

namespace Tests\Unit\Presenters\Cta;

use App\Models\Cta;
use App\Models\Event;
use App\Models\Page;
use App\Models\Site;
use App\Presenters\Cta\EventTypePresenter;
use App\Services\Pageable\EventPageSaver;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EventTypePresenterTest extends TestCase
{
    use RefreshDatabase;

    public function test_partial_path()
    {
        $cta = new Cta();
        $presenter = (new EventTypePresenter())->setWrappedObject($cta);

        $result = $presenter->partialPath();

        $this->assertEquals('ctas._event', $result);
    }

    public function test_displayable_when_event_exists(): void
    {
        $site = factory(Site::class)->state('default')->create();
        $this->app->instance('currentSite', $site);
        $todaysEvent = factory(Event::class)->create([
            'start_date' => Carbon::today(),
        ]);
        $todaysEventPage = $this->createEventPage($todaysEvent, $site);
        $cta = new Cta();
        $presenter = (new EventTypePresenter())->setWrappedObject($cta);

        $this->assertTrue($presenter->displayable());
    }

    public function test_displayable_when_no_event(): void
    {
        $site = factory(Site::class)->state('default')->create();
        $this->app->instance('currentSite', $site);
        $cta = new Cta();
        $presenter = (new EventTypePresenter())->setWrappedObject($cta);

        $this->assertFalse($presenter->displayable());
    }

    public function test_page_when_event_exists(): void
    {
        $site = factory(Site::class)->state('default')->create();
        $this->app->instance('currentSite', $site);
        $todaysEvent = factory(Event::class)->create([
            'start_date' => Carbon::today(),
        ]);
        $todaysEventPage = $this->createEventPage($todaysEvent, $site);
        $cta = new Cta();
        $presenter = (new EventTypePresenter())->setWrappedObject($cta);

        $this->assertEquals($todaysEventPage->id, $presenter->page()->id);
    }

    public function test_page_when_no_event_exists(): void
    {
        $site = factory(Site::class)->state('default')->create();
        $this->app->instance('currentSite', $site);
        $cta = new Cta();
        $presenter = (new EventTypePresenter())->setWrappedObject($cta);

        $this->assertNull($presenter->page()->id);
    }

    public function test_event_dates(): void
    {
        $site = factory(Site::class)->state('default')->create();
        $this->app->instance('currentSite', $site);
        $event = factory(Event::class)->create([
            'start_date' => Carbon::today(),
        ]);
        $todaysEventPage = $this->createEventPage($event, $site);
        $cta = new Cta();
        $presenter = (new EventTypePresenter())->setWrappedObject($cta);

        $expected = "{$event->start_date->format('d F Y')} - {$event->end_date->format('d F Y')}";
        $this->assertEquals($expected, $presenter->eventDates());
    }

    public function test_event_dates_when_no_event(): void
    {
        $site = factory(Site::class)->state('default')->create();
        $this->app->instance('currentSite', $site);
        $cta = new Cta();
        $presenter = (new EventTypePresenter())->setWrappedObject($cta);

        $this->assertEquals("", $presenter->eventDates());
    }

    public function test_event_name(): void
    {
        $site = factory(Site::class)->state('default')->create();
        $this->app->instance('currentSite', $site);
        $event = factory(Event::class)->create([
            'start_date' => Carbon::today(),
        ]);
        $todaysEventPage = $this->createEventPage($event, $site);
        $cta = new Cta();
        $presenter = (new EventTypePresenter())->setWrappedObject($cta);

        $this->assertEquals($event->name, $presenter->eventName());
    }

    public function test_event_name_when_no_event(): void
    {
        $site = factory(Site::class)->state('default')->create();
        $this->app->instance('currentSite', $site);
        $cta = new Cta();
        $presenter = (new EventTypePresenter())->setWrappedObject($cta);

        $this->assertEquals("", $presenter->eventName());
    }

    public function test_fetches_latest_event(): void
    {
        $site = factory(Site::class)->state('default')->create();
        $this->app->instance('currentSite', $site);
        $pastEvent = factory(Event::class)->create([
            'start_date' => Carbon::yesterday(),
        ]);
        $pastEventPage = $this->createEventPage($pastEvent, $site);
        $todaysEvent = factory(Event::class)->create([
            'start_date' => Carbon::today(),
        ]);
        $todaysEventPage = $this->createEventPage($todaysEvent, $site);
        $futureEvent = factory(Event::class)->create([
            'start_date' => Carbon::tomorrow(),
        ]);
        $futureEventPage = $this->createEventPage($futureEvent, $site);
        $cta = new Cta();
        $presenter = (new EventTypePresenter())->setWrappedObject($cta);

        $nextEventPage = $presenter->page();
        $this->assertNotNull($nextEventPage);
        $this->assertEquals($nextEventPage->id, $todaysEventPage->id);
    }

    private function createEventPage(Event $event, Site $site): Page
    {
        $saver = new EventPageSaver($event, $site);
        $saver->call();
        return $event->pages()->first();
    }
}
