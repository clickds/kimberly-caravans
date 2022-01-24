<?php

namespace Tests\Feature\Admin\Seats;

use App\Models\Seat;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class UpdateTest extends TestCase
{
    use RefreshDatabase;
    public function test_required_validation(): void
    {
        $seat = factory(Seat::class)->create();
        $data = $this->validData([
            'number' => null,
        ]);

        $response = $this->submit($seat, $data);

        $response->assertSessionHasErrors('number');
    }

    public function test_unique_validation(): void
    {
        $seat = factory(Seat::class)->create();
        $otherSeat = factory(Seat::class)->create();
        $data = $this->validData([
            'number' => $otherSeat->number,
        ]);

        $response = $this->submit($seat, $data);

        $response->assertSessionHasErrors('number');
    }

    public function test_successful(): void
    {
        $seat = factory(Seat::class)->create();
        $data = $this->validData([
            'number' => $seat->number,
        ]);

        $response = $this->submit($seat, $data);

        $response->assertRedirect(route('admin.seats.index'));
        $this->assertDatabaseHas('seats', $data);
    }

    private function validData(array $overrides = []): array
    {
        $defaults = [
            'number' => 1,
        ];

        return array_merge($defaults, $overrides);
    }

    private function submit(Seat $seat, array $data): TestResponse
    {
        $user = $this->createSuperUser();
        $url = route('admin.seats.update', $seat);

        return $this->actingAs($user)->put($url, $data);
    }
}
