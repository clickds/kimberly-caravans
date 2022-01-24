<?php

namespace Tests\Feature\Admin\Site\OpeningTimes;

use App\Models\OpeningTime;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class DestroyTest extends TestCase
{
    use RefreshDatabase;

    public function test_destroys_opening_time(): void
    {
        $openingTime = factory(OpeningTime::class)->create();

        $response = $this->submit($openingTime);

        $response->assertRedirect(route('admin.sites.opening-times.index', $openingTime->site));
        $this->assertDatabaseMissing('opening_times', $openingTime->getAttributes());
    }

    private function submit(OpeningTime $openingTime): TestResponse
    {
        $user = $this->createSuperUser();
        $url = route('admin.sites.opening-times.destroy', [
            'site' => $openingTime->site,
            'opening_time' => $openingTime,
        ]);

        return $this->actingAs($user)->delete($url);
    }
}
