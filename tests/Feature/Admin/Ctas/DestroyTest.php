<?php

namespace Tests\Feature\Admin\Ctas;

use App\Models\Cta;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class DestroyTest extends TestCase
{
    use RefreshDatabase;

    public function test_deletes_successfully()
    {
        $cta = $this->createCta();

        $response = $this->submit($cta);

        $response->assertRedirect(route('admin.ctas.index'));
        $this->assertDatabaseMissing('ctas', $cta->getAttributes());
    }

    private function submit(Cta $cta): TestResponse
    {
        $user = $this->createSuperUser();
        $url = route('admin.ctas.destroy', $cta);

        return $this->actingAs($user)->delete($url);
    }

    private function createCta(array $attributes = []): Cta
    {
        return factory(Cta::class)->create($attributes);
    }
}
