<?php

namespace Tests\Feature\Admin\Events;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Event;

class DestroyTest extends TestCase
{
    use RefreshDatabase;

    public function test_successful()
    {
        $event = $this->createEvent();

        $response = $this->submit($event);

        $response->assertRedirect(route('admin.events.index'));
        $this->assertDatabaseMissing('events', [
            'id' => $event->id,
        ]);
    }

    public function test_removes_site_pages()
    {
        $site = $this->createSite();
        $event = $this->createEvent();
        $event->sites()->sync($site);
        $page = $this->createPageForPageable($event, $site);

        $response = $this->submit($event);

        $response->assertRedirect(route('admin.events.index'));
        $this->assertDatabaseMissing('events', [
            'id' => $event->id,
        ]);
        $this->assertDatabaseMissing('pageable_site', [
            'pageable_type' => Event::class,
            'pageable_id' => $event->id,
            'site_id' => $site->id,
        ]);
        $this->assertDatabaseMissing('pages', [
            'id' => $page->id,
        ]);
    }

    private function submit(Event $event)
    {
        $admin = $this->createSuperUser();
        $url = $this->url($event);

        return $this->actingAs($admin)->delete($url);
    }

    private function url(Event $event)
    {
        return route('admin.events.destroy', $event);
    }

    private function createEvent()
    {
        return factory(Event::class)->create();
    }
}
