<?php

namespace Tests\Feature\Admin\Motorhome\OptionalWeights;

use App\Models\OptionalWeight;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class DestroyTest extends TestCase
{
    use RefreshDatabase;

    public function test_successfully_destroys_optional_weight()
    {
        $optionalWeight = factory(OptionalWeight::class)->create();

        $response = $this->submit($optionalWeight);

        $response->assertRedirect($this->redirectUrl($optionalWeight));
        $this->assertDatabaseMissing('optional_weights', $optionalWeight->getAttributes());
    }

    private function submit(OptionalWeight $optionalWeight): TestResponse
    {
        $user = $this->createSuperUser();
        $url = route('admin.motorhomes.optional-weights.destroy', [
            'motorhome' => $optionalWeight->motorhome,
            'optional_weight' => $optionalWeight,
        ]);

        return $this->actingAs($user)->delete($url);
    }

    private function redirectUrl(OptionalWeight $optionalWeight): string
    {
        return route('admin.motorhomes.optional-weights.index', $optionalWeight->motorhome);
    }
}
