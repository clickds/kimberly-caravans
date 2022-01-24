<?php

namespace Tests\Feature\Admin\Motorhome\OptionalWeights;

use App\Models\Motorhome;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class StoreTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @dataProvider requiredProvider
     */
    public function test_required_validation(string $inputName): void
    {
        $motorhome = factory(Motorhome::class)->create();
        $data = $this->validData([
            $inputName => null,
        ]);

        $response = $this->submit($motorhome, $data);

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
        $motorhome = factory(Motorhome::class)->create();
        $data = $this->validData();

        $response = $this->submit($motorhome, $data);

        $response->assertRedirect($this->redirectUrl($motorhome));
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

    private function submit(Motorhome $motorhome, array $data): TestResponse
    {
        $user = $this->createSuperUser();
        $url = route('admin.motorhomes.optional-weights.store', $motorhome);

        return $this->actingAs($user)->post($url, $data);
    }

    private function redirectUrl(Motorhome $motorhome): string
    {
        return route('admin.motorhomes.optional-weights.index', $motorhome);
    }
}
