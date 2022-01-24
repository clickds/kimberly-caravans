<?php

namespace Tests\Feature\Api\Admin\SearchPages;

use App\Models\Page;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class WithoutSiteTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_searches_by_exact_name()
    {
        $page = $this->createPage();
        $data = [
            'search_term' => $page->name,
        ];

        $response = $this->submit($data);

        $response->assertJsonFragment([
            'id' => $page->id,
            'name' => $page->name,
        ]);
        $response->assertJsonFragment([
            'id' => $page->site->id,
            'country' => $page->site->country,
        ]);
    }

    public function test_it_searches_by_partial_match_at_start_of_name()
    {
        $page = $this->createPage([
            'name' => 'abcdef',
        ]);
        $data = [
            'search_term' => 'abc',
        ];

        $response = $this->submit($data);

        $response->assertJsonFragment([
            'id' => $page->id,
            'name' => $page->name,
        ]);
    }

    public function test_it_searches_by_partial_match_in_middle_of_name()
    {
        $page = $this->createPage([
            'name' => 'abcdef',
        ]);
        $data = [
            'search_term' => 'cde',
        ];

        $response = $this->submit($data);

        $response->assertJsonFragment([
            'id' => $page->id,
            'name' => $page->name,
        ]);
    }

    public function test_it_searches_by_partial_match_at_end_of_name()
    {
        $page = $this->createPage([
            'name' => 'abcdef',
        ]);
        $data = [
            'search_term' => 'def',
        ];

        $response = $this->submit($data);

        $response->assertJsonFragment([
            'id' => $page->id,
            'name' => $page->name,
        ]);
    }

    public function test_it_searches_are_case_insensitive()
    {
        $page = $this->createPage([
            'name' => 'abcdef',
        ]);
        $data = [
            'search_term' => 'def',
        ];

        $response = $this->submit($data);

        $response->assertJsonFragment([
            'id' => $page->id,
            'name' => $page->name,
        ]);
    }

    private function submit(array $data): TestResponse
    {
        $admin = $this->createSuperUser();
        $url = route('api.admin.search-pages', $data);

        return $this->actingAs($admin)->getJson($url);
    }

    private function createPage(array $attributes = [])
    {
        return factory(Page::class)->create($attributes);
    }
}
