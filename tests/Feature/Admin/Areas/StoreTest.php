<?php

namespace Tests\Feature\Admin\Areas;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Area;
use App\Models\Page;

class StoreTest extends TestCase
{
    use RefreshDatabase;
    public function test_it_requires_a_name()
    {
        $page = factory(Page::class)->create();
        $data = $this->validFields(['name' => '']);

        $response = $this->submit($page, $data);

        $response->assertSessionHasErrors('name');
    }

    public function test_it_requires_columns()
    {
        $page = factory(Page::class)->create();
        $data = $this->validFields(['columns' => '']);

        $response = $this->submit($page, $data);

        $response->assertSessionHasErrors('columns');
    }

    public function test_columns_must_be_in_number_of_columns_constant()
    {
        $page = factory(Page::class)->create();
        $data = $this->validFields(['columns' => 0]);

        $response = $this->submit($page, $data);

        $response->assertSessionHasErrors('columns');
    }

    public function test_it_requires_a_holder()
    {
        $page = factory(Page::class)->create();
        $data = $this->validFields(['holder' => '']);

        $response = $this->submit($page, $data);

        $response->assertSessionHasErrors('holder');
    }

    public function test_live_is_required()
    {
        $page = factory(Page::class)->create();
        $data = $this->validFields(['live' => '']);

        $response = $this->submit($page, $data);

        $response->assertSessionHasErrors('live');
    }

    public function test_holder_is_valid()
    {
        $this->markTestIncomplete('To be determined when holders defined');
    }

    public function test_it_requires_a_background_colour()
    {
        $page = factory(Page::class)->create();
        $data = $this->validFields(['background_colour' => '']);

        $response = $this->submit($page, $data);

        $response->assertSessionHasErrors('background_colour');
    }

    public function test_background_colour_must_be_in_background_colours_constant()
    {
        $page = factory(Page::class)->create();
        $data = $this->validFields(['background_colour' => 'taupe']);

        $response = $this->submit($page, $data);

        $response->assertSessionHasErrors('background_colour');
    }

    public function test_it_requires_a_width()
    {
        $page = factory(Page::class)->create();
        $data = $this->validFields(['width' => '']);

        $response = $this->submit($page, $data);

        $response->assertSessionHasErrors('width');
    }

    public function test_it_requires_width_to_be_in_the_widths_constant()
    {
        $page = factory(Page::class)->create();
        $data = $this->validFields(['width' => 'blah']);

        $response = $this->submit($page, $data);

        $response->assertSessionHasErrors('width');
    }

    public function test_successful()
    {
        $page = factory(Page::class)->create();
        $data = $this->validFields();

        $response = $this->submit($page, $data);

        $response->assertRedirect($this->redirectUrl($page));
    }

    private function submit(Page $page, array $data)
    {
        $admin = $this->createSuperUser();
        $url = $this->url($page);

        return $this->actingAs($admin)->post($url, $data);
    }

    private function validFields($overrides = [])
    {
        $defaults = [
            'background_colour' => array_keys(Area::BACKGROUND_COLOURS)[0],
            'heading_text_alignment' => array_keys(Area::TEXT_ALIGNMENTS)[0],
            'columns' => Area::COLUMNS[0],
            'name' => 'some name',
            'holder' => 'Primary',
            'width' => array_keys(Area::WIDTHS)[0],
            'live' => false,
            'heading_type' => Area::HEADING_TYPES[0],
        ];

        return array_merge($defaults, $overrides);
    }

    private function url(Page $page)
    {
        return route('admin.pages.areas.store', $page);
    }

    private function redirectUrl(Page $page)
    {
        return route('admin.pages.areas.index', $page);
    }
}
