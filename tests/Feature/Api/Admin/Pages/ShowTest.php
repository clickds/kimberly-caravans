<?php

namespace Tests\Feature\Api\Admin\Pages;

use App\Models\Page;
use App\Models\Site;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class ShowTest extends TestCase
{
    use RefreshDatabase;

    public function test_fetching_existing_page()
    {
        $page = $this->createPage();

        $response = $this->submit($page);

        $response->assertStatus(200);
        $response->assertJsonFragment([
            'id' => $page->id,
            'name' => $page->name,
        ]);
    }

    public function test_when_page_does_not_exist()
    {
        $response = $this->submit(0);

        $response->assertStatus(404);
    }

    private function submit($page): TestResponse
    {
        $admin = $this->createSuperUser();
        $url = route('api.admin.pages.show', [
            'page' => $page,
        ]);

        return $this->actingAs($admin)->getJson($url);
    }

    private function createPage(array $attributes = [])
    {
        return factory(Page::class)->create($attributes);
    }
}
