<?php

namespace Tests\Feature\Admin\Motorhome\OptionalWeights;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;
use App\Models\OptionalWeight;

class UpdateTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @dataProvider requiredProvider
     */
    public function test_required_validation(string $inputName): void
    {
        $optionalWeight = factory(OptionalWeight::class)->create();
        $data = $this->validData([
            $inputName => null,
        ]);

        $response = $this->submit($optionalWeight, $data);

        $response->assertSessionHasErrors($inputName);
    }

    public function requiredProvider(): array
    {
        return [
            ['name'],
            ['value'],
        ];
    }

    public function test_updates_optional_weight(): void
    {
        $optionalWeight = factory(OptionalWeight::class)->create();
        $data = $this->validData();

        $response = $this->submit($optionalWeight, $data);

        $response->assertRedirect($this->redirectUrl($optionalWeight));
        $this->assertDatabaseHas('optional_weights', $data);
    }

    private function validData(array $overrides = []): array
    {
        $defaults = [
            'name' => 'some name',
            'value' => 'some value',
        ];

        return array_merge($defaults, $overrides);
    }

    private function submit(OptionalWeight $optionalWeight, array $data): TestResponse
    {
        $user = $this->createSuperUser();
        $url = route('admin.motorhomes.optional-weights.update', [
            'motorhome' => $optionalWeight->motorhome,
            'optional_weight' => $optionalWeight,
        ]);

        return $this->actingAs($user)->put($url, $data);
    }

    private function redirectUrl(OptionalWeight $optionalWeight): string
    {
        return route('admin.motorhomes.optional-weights.index', $optionalWeight->motorhome);
    }
}
