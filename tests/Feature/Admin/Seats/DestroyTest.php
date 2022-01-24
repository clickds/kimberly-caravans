<?php

namespace Tests\Feature\Admin\Seats;

use App\Models\Seat;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class DestroyTest extends TestCase
{
    use RefreshDatabase;

    public function test_delete_successfully(): void
    {
        $seat = factory(Seat::class)->create();

        $response = $this->submit($seat);

        $response->assertRedirect();
        $this->assertDatabaseMissing('seats', $seat->getAttributes());
    }

    private function submit(Seat $seat): TestResponse
    {
        $user = $this->createSuperUser();
        $url = route('admin.seats.destroy', $seat);

        return $this->actingAs($user)->delete($url);
    }
}
