<?php

namespace Tests\Feature\Admin\Berths;

use App\Models\Berth;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class UpdateTest extends TestCase
{
    use RefreshDatabase;
    public function test_required_validation(): void
    {
        $berth = factory(Berth::class)->create();
        $data = $this->validData([
            'number' => null,
        ]);

        $response = $this->submit($berth, $data);

        $response->assertSessionHasErrors('number');
    }

    public function test_unique_validation(): void
    {
        $berth = factory(Berth::class)->create();
        $otherBerth = factory(Berth::class)->create();
        $data = $this->validData([
            'number' => $otherBerth->number,
        ]);

        $response = $this->submit($berth, $data);

        $response->assertSessionHasErrors('number');
    }

    public function test_successful(): void
    {
        $berth = factory(Berth::class)->create();
        $data = $this->validData([
            'number' => $berth->number,
        ]);

        $response = $this->submit($berth, $data);

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

    private function submit(Berth $berth, array $data): TestResponse
    {
        $user = $this->createSuperUser();
        $url = route('admin.berths.update', $berth);

        return $this->actingAs($user)->put($url, $data);
    }
}
