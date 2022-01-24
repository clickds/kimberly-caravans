<?php

namespace Tests\Feature\Admin\Testimonials;

use App\Models\Site;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\TestimonialCategory;
use App\Models\Testimonial;
use Illuminate\Support\Arr;

class StoreTest extends TestCase
{
    use RefreshDatabase;

    public function test_successful()
    {
        $site = factory(Site::class)->create();
        $data = $this->validFields();
        $data['site_ids'] = [$site->id];

        $response = $this->submit($data);

        $response->assertRedirect(route('admin.testimonials.index'));
        $data = Arr::except($data, 'site_ids');
        $this->assertDatabaseHas('testimonials', $data);
        $testimonial = Testimonial::first();
        $this->assertDatabaseHas('site_testimonial', [
            'site_id' => $site->id,
            'testimonial_id' => $testimonial->id,
        ]);
    }

    public function test_it_requires_a_name()
    {
        $data = $this->validFields(['name' => '']);

        $response = $this->submit($data);

        $response->assertSessionHasErrors('name');
    }


    public function test_it_requires_published_at_to_be_a_date()
    {
        $data = $this->validFields(['published_at' => 'abc']);

        $response = $this->submit($data);

        $response->assertSessionHasErrors('published_at');
    }

    public function test_it_requires_content()
    {
        $data = $this->validFields(['content' => '']);

        $response = $this->submit($data);

        $response->assertSessionHasErrors('content');
    }

    private function submit($data = [])
    {
        $admin = $this->createSuperUser();
        $url = $this->url();

        return $this->actingAs($admin)->post($url, $data);
    }

    private function validFields($overrides = [])
    {
        $defaults = [
            'name' => 'some name',
            'content' => 'some excerpt',
            'position' => 0,
            'published_at' => now(),
        ];

        return array_merge($defaults, $overrides);
    }

    private function url()
    {
        return route('admin.testimonials.store');
    }
}
