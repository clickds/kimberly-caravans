<?php

namespace Tests\Feature\Admin\Berths;

use App\Models\Berth;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class StoreTest extends TestCase
{
    use RefreshDatabase;

    public function test_required_validation(): void
    {
        $data = $this->validData([
            'number' => null,
        ]);

        $response = $this->submit($data);

        $response->assertSessionHasErrors('number');
    }

    public function test_unique_validation(): void
    {
        $berth = factory(Berth::class)->create();
        $data = $this->validData([
            'number' => $berth->number,
        ]);

        $response = $this->submit($data);

        $response->assertSessionHasErrors('number');
    }

    public function test_successful(): void
    {
        $data = $this->validData();

        $response = $this->submit($data);

        $response->assertRedirect(route('admin.berths.index'));
        $this->assertDatabaseHas('berths', $data);
    }

    private function validData(array $overrides = []): array
    {
        $defaults = [
            'number' => 1,
        ];

        return array_merge($defaults, $overrides);
    }

    private function submit(array $data): TestResponse
    {
        $user = $this->createSuperUser();
        $url = route('admin.berths.store');

        return $this->actingAs($user)->post($url, $data);
    }
}
