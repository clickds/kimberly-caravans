<?php

namespace Tests\Feature\Admin\Testimonials;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Testimonial;

class DestroyTest extends TestCase
{
    use RefreshDatabase;

    public function test_successful()
    {
        $testimonial = $this->createTestimonial();

        $response = $this->submit($testimonial);

        $response->assertRedirect(route('admin.testimonials.index'));
        $this->assertDatabaseMissing('testimonials', [
            'id' => $testimonial->id,
        ]);
    }

    private function submit(Testimonial $testimonial)
    {
        $admin = $this->createSuperUser();
        $url = $this->url($testimonial);

        return $this->actingAs($admin)->delete($url);
    }

    private function url(Testimonial $testimonial)
    {
        return route('admin.testimonials.destroy', $testimonial);
    }

    private function createTestimonial()
    {
        return factory(Testimonial::class)->create();
    }
}
