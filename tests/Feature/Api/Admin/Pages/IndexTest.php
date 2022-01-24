<?php

namespace Tests\Feature\Api\Admin\Pages;

use App\Models\Page;
use App\Models\Site;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class IndexTest extends TestCase
{
    use RefreshDatabase;

    public function test_fetching_existing_page()
    {
        $page = $this->createPage();
        $otherPage = $this->createPage();

        $response = $this->submit([
            'ids' => [$page->id],
        ]);

        $response->assertStatus(200);
        $response->assertJsonFragment([
            'id' => $page->id,
            'name' => $page->name,
        ]);
        $response->assertJsonMissing([
            'id' => $otherPage->id,
            'name' => $otherPage->name,
        ]);
    }

    private function submit(array $data = []): TestResponse
    {
        $admin = $this->createSuperUser();
        $url = route('api.admin.pages.index', $data);

        return $this->actingAs($admin)->getJson($url);
    }

    private function createPage(array $attributes = [])
    {
        return factory(Page::class)->create($attributes);
    }
}
