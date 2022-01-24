<?php

namespace Tests\Feature\Admin\Dealer\Employees;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;
use App\Models\Dealer;

class StoreTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function test_successful()
    {
        $data = $this->validFields();

        $dealer = $this->createDealer();

        $response = $this->submit($dealer, $data);

        $response->assertRedirect(route('admin.dealers.employees.index', $dealer));

        $this->assertDatabaseHas('dealer_employees', $data);
    }

    public function test_name_is_required()
    {
        $data = $this->validFields(['name' => null]);

        $dealer = $this->createDealer();

        $response = $this->submit($dealer, $data);

        $response->assertSessionHasErrors('name');
    }

    public function test_role_is_required()
    {
        $data = $this->validFields(['role' => null]);

        $dealer = $this->createDealer();

        $response = $this->submit($dealer, $data);

        $response->assertSessionHasErrors('role');
    }

    private function submit(Dealer $dealer, array $data): TestResponse
    {
        $admin = $this->createSuperUser();

        $url = $this->url($dealer);

        return $this->actingAs($admin)->post($url, $data);
    }

    private function validFields(array $overrides = []): array
    {
        $defaults = [
            'name' => $this->faker->name,
            'role' => $this->faker->jobTitle,
            'phone' => $this->faker->phoneNumber,
            'email' => $this->faker->companyEmail,
            'position' => $this->faker->randomNumber(1),
        ];

        return array_merge($defaults, $overrides);
    }

    private function url(Dealer $dealer): string
    {
        return route('admin.dealers.employees.store', $dealer);
    }

    private function createDealer(): Dealer
    {
        return factory(Dealer::class)->create();
    }
}
