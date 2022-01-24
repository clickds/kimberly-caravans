<?php

namespace Tests\Feature\Admin\Aliases;

use App\Models\Alias;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class DestroyTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function test_deletes_alias(): void
    {
        $alias = factory(Alias::class)->create();

        $response = $this->submit($alias);

        $response->assertRedirect(route('admin.aliases.index'));
        $this->assertDatabaseMissing('aliases', $alias->getAttributes());
    }

    private function submit(Alias $alias): TestResponse
    {
        $user = $this->createSuperUser();
        $url = route('admin.aliases.destroy', $alias);

        return $this->actingAs($user)->delete($url);
    }
}
