<?php

namespace Tests\Feature\Admin\Users;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class DestroyTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function test_successful(): void
    {
        $user = factory(User::class)->create();

        $superUser = $this->createSuperUser();

        $response = $this->submit($superUser, $user);

        $response->assertRedirect(route('admin.users.index'));
    }

    public function test_regular_admin_cant_delete_other_users()
    {
        $this->markTestIncomplete();
    }

    private function submit(User $adminUser, User $userToDelete): TestResponse
    {
        $url = route('admin.users.destroy', $userToDelete);

        return $this->actingAs($adminUser)->delete($url);
    }
}
