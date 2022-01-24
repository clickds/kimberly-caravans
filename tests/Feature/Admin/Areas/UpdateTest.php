<?php

namespace Tests\Feature\Admin\Areas;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Area;

class UpdateTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_requires_a_name()
    {
        $area = factory(Area::class)->create();
        $data = $this->validFields(['name' => '']);

        $response = $this->submit($area, $data);

        $response->assertSessionHasErrors('name');
    }

    public function test_it_requires_columns()
    {
        $area = factory(Area::class)->create();
        $data = $this->validFields(['columns' => '']);

        $response = $this->submit($area, $data);

        $response->assertSessionHasErrors('columns');
    }

    public function test_columns_must_be_in_number_of_columns_constant()
    {
        $area = factory(Area::class)->create();
        $data = $this->validFields(['columns' => 0]);

        $response = $this->submit($area, $data);

        $response->assertSessionHasErrors('columns');
    }

    public function test_it_requires_a_holder()
    {
        $area = factory(Area::class)->create();
        $data = $this->validFields(['holder' => '']);

        $response = $this->submit($area, $data);

        $response->assertSessionHasErrors('holder');
    }

    public function test_holder_is_valid()
    {
        $this->markTestIncomplete('To be determined when holders defined');
    }

    public function test_live_is_required()
    {
        $area = factory(Area::class)->create();
        $data = $this->validFields(['live' => '']);

        $response = $this->submit($area, $data);

        $response->assertSessionHasErrors('live');
    }

    public function test_it_requires_a_background_colour()
    {
        $area = factory(Area::class)->create();
        $data = $this->validFields(['background_colour' => '']);

        $response = $this->submit($area, $data);

        $response->assertSessionHasErrors('background_colour');
    }

    public function test_background_colour_must_be_in_background_colours_constant()
    {
        $area = factory(Area::class)->create();
        $data = $this->validFields(['background_colour' => 'taupe']);

        $response = $this->submit($area, $data);

        $response->assertSessionHasErrors('background_colour');
    }

    public function test_it_requires_a_width()
    {
        $area = factory(Area::class)->create();
        $data = $this->validFields(['width' => '']);

        $response = $this->submit($area, $data);

        $response->assertSessionHasErrors('width');
    }

    public function test_it_requires_width_to_be_in_the_widths_constant()
    {
        $area = factory(Area::class)->create();
        $data = $this->validFields(['width' => 'blah']);

        $response = $this->submit($area, $data);

        $response->assertSessionHasErrors('width');
    }

    public function test_successful()
    {
        $area = factory(Area::class)->create();
        $data = $this->validFields();

        $response = $this->submit($area, $data);

        $response->assertRedirect($this->redirectUrl($area));
    }

    public function test_when_redirect_url()
    {
        $area = factory(Area::class)->create();
        $redirect_url = route('site', [
            'page' => $area->page->slug,
        ]);
        $data = $this->validFields([
            'name' => $area->name,
            'holder' => $area->holder,
            'heading' => $area->heading,
            'layout' => $area->layout,
            'background_colour' => $area->background_colour,
            'redirect_url' => $redirect_url,
        ]);

        $response = $this->submit($area, $data);

        $response->assertRedirect($redirect_url);
    }

    public function test_when_no_redirect_url()
    {
        $area = factory(Area::class)->create();
        $data = $this->validFields([
            'name' => $area->name,
            'holder' => $area->holder,
            'heading' => $area->heading,
            'layout' => $area->layout,
            'background_colour' => $area->background_colour,
        ]);

        $response = $this->submit($area, $data);

        $response->assertRedirect($this->redirectUrl($area));
    }

    private function submit(Area $area, array $data)
    {
        $admin = $this->createSuperUser();
        $url = $this->url($area);

        return $this->actingAs($admin)->put($url, $data);
    }

    private function validFields($overrides = [])
    {
        $defaults = [
            'background_colour' => array_rand(Area::BACKGROUND_COLOURS),
            'heading_text_alignment' => array_keys(Area::TEXT_ALIGNMENTS)[0],
            'columns' => Area::COLUMNS[0],
            'name' => 'some name',
            'holder' => 'Primary',
            'width' => array_rand(Area::WIDTHS),
            'live' => false,
            'heading_type' => Area::HEADING_TYPES[0],
        ];

        return array_merge($defaults, $overrides);
    }

    private function redirectUrl(Area $area)
    {
        return route('admin.pages.areas.index', [
            'page' => $area->page,
        ]);
    }

    private function url(Area $area)
    {
        return route('admin.pages.areas.update', [
            'page' => $area->page,
            'area' => $area,
        ]);
    }
}
