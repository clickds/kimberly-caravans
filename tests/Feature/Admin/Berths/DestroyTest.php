<?php

namespace Tests\Feature\Admin\Berths;

use App\Models\Berth;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class DestroyTest extends TestCase
{
    use RefreshDatabase;

    public function test_delete_successfully(): void
    {
        $berth = factory(Berth::class)->create();

        $response = $this->submit($berth);

        $response->assertRedirect();
        $this->assertDatabaseMissing('berths', $berth->getAttributes());
    }

    private function submit(Berth $berth): TestResponse
    {
        $user = $this->createSuperUser();
        $url = route('admin.berths.destroy', $berth);

        return $this->actingAs($user)->delete($url);
    }
}
