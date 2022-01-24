<?php

namespace Tests\Feature\Admin\Users;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Arr;
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
            ['password'],
            ['super'],
        ];
    }

    public function test_email_is_unique(): void
    {
        $user = factory(User::class)->create();
        $data = $this->validData([
            'email' => $user->email,
        ]);

        $response = $this->submit($data);

        $response->assertSessionHasErrors('email');
    }

    public function test_successful(): void
    {
        $data = $this->validData();

        $response = $this->submit($data);

        $response->assertRedirect();
        $userData = Arr::except($data, ['password']);
        $this->assertDatabaseHas('users', $userData);
    }

    private function validData(array $overrides = []): array
    {
        $defaults = [
            'name' => $this->faker->name,
            'email' => $this->faker->unique()->email,
            'password' => 'password',
            'super' => true,
        ];

        return array_merge($defaults, $overrides);
    }

    private function submit(array $data): TestResponse
    {
        $user = $this->createSuperUser();
        $url = route('admin.users.store');

        return $this->actingAs($user)->post($url, $data);
    }
}
