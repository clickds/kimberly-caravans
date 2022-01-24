<?php

namespace Tests\Feature\Admin\Testimonials;

use App\Models\Site;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Testimonial;
use Illuminate\Support\Arr;

class UpdateTest extends TestCase
{
    use RefreshDatabase;

    public function test_successful()
    {
        $testimonial = $this->createTestimonial();
        $site = factory(Site::class)->create();
        $data = $this->validFields($testimonial);
        $data['site_ids'] = [$site->id];

        $response = $this->submit($testimonial, $data);

        $response->assertRedirect(route('admin.testimonials.index'));
        $data = Arr::except($data, 'site_ids');
        $this->assertDatabaseHas('testimonials', array_merge($data, ['id' => $testimonial->id]));
        $this->assertDatabaseHas('site_testimonial', [
            'site_id' => $site->id,
            'testimonial_id' => $testimonial->id,
        ]);
    }

    public function test_it_requires_a_name()
    {
        $testimonial = $this->createTestimonial();
        $data = $this->validFields($testimonial, ['name' => '']);

        $response = $this->submit($testimonial, $data);

        $response->assertSessionHasErrors('name');
    }

    public function test_it_requires_published_at_to_be_a_time()
    {
        $testimonial = $this->createTestimonial();
        $data = $this->validFields($testimonial, ['published_at' => 'abc']);

        $response = $this->submit($testimonial, $data);

        $response->assertSessionHasErrors('published_at');
    }

    public function test_it_requires_content()
    {
        $testimonial = $this->createTestimonial();
        $data = $this->validFields($testimonial, ['content' => '']);

        $response = $this->submit($testimonial, $data);

        $response->assertSessionHasErrors('content');
    }

    private function submit(Testimonial $testimonial, $data = [])
    {
        $admin = $this->createSuperUser();
        $url = $this->url($testimonial);

        return $this->actingAs($admin)->put($url, $data);
    }

    private function validFields(Testimonial $testimonial, $overrides = [])
    {
        $defaults = [
            'name' => 'some name',
            'content' => 'some excerpt',
            'position' => 0,
            'published_at' => now(),
        ];

        return array_merge($defaults, $overrides);
    }

    private function url(Testimonial $testimonial)
    {
        return route('admin.testimonials.update', $testimonial);
    }

    private function createTestimonial()
    {
        return factory(Testimonial::class)->create();
    }
}
