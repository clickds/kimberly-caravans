<?php

namespace Tests\Feature\Api\Admin\SpecialOffers;

use App\Models\SpecialOffer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class IndexTest extends TestCase
{
    use RefreshDatabase;

    public function test_successful()
    {
        $specialOffers = factory(SpecialOffer::class, 10)->create();
        $response = $this->submit();

        $response->assertStatus(200);
        foreach ($specialOffers as $specialOffer) {
            $response->assertJsonFragment([
                'id' => $specialOffer->id,
                'name' => $specialOffer->name,
            ]);
        }
    }

    private function submit()
    {
        $user = $this->createSuperUser();
        $url = $this->url();
        return $this->actingAs($user)->get($url);
    }

    private function url()
    {
        return route('api.admin.special-offers.index');
    }
}
