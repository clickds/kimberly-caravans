<?php

namespace Tests\Feature\Admin\BusinessAreas;

use App\Models\BusinessArea;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class StoreTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /**
     * @dataProvider requiredProvider
     */
    public function test_required_validation(string $inputName): void
    {
        $data = $this->validData([
            $inputName => null,
        ]);

        $response = $this->submit($data);

        $response->assertSessionHasErrors($inputName);
    }

    public function requiredProvider(): array
    {
        return [
            ['name'],
            ['email'],
        ];
    }

    public function test_email_validation(): void
    {
        $data = $this->validData([
            'email' => 'abc',
        ]);

        $response = $this->submit($data);

        $response->assertSessionHasErrors('email');
    }

    public function test_unique_validation(): void
    {
        $existingBusinessArea = factory(BusinessArea::class)->create();
        $data = $this->validData(['name' => $existingBusinessArea->name]);
        $response = $this->submit($data);
        $response->assertSessionHasErrors('name');
    }

    public function test_successful(): void
    {
        $data = $this->validData();
        $response = $this->submit($data);
        $response->assertRedirect(route('admin.business-areas.index'));
        $this->assertDatabaseHas('business_areas', $data);
    }

    private function submit(array $data): TestResponse
    {
        $user = $this->createSuperUser();
        $url = route('admin.business-areas.store');

        return $this->actingAs($user)->post($url, $data);
    }

    private function validData(array $overrides = []): array
    {
        $defaults = [
            'name' => $this->faker->name,
            'email' => $this->faker->unique()->email,
        ];

        return array_merge($defaults, $overrides);
    }
}
