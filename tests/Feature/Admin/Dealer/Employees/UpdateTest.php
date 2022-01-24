<?php

namespace Tests\Feature\Admin\Dealer\Employees;

use App\Models\DealerEmployee;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;
use App\Models\Dealer;

class UpdateTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function test_successful()
    {
        $data = $this->validFields();

        $employee = $this->createEmployee();

        $response = $this->submit($employee, $data);

        $response->assertRedirect(route('admin.dealers.employees.index', $employee->dealer));

        $this->assertDatabaseHas('dealer_employees', $data);
    }

    public function test_name_is_required()
    {
        $data = $this->validFields(['name' => null]);

        $employee = $this->createEmployee();

        $response = $this->submit($employee, $data);

        $response->assertSessionHasErrors('name');
    }

    public function test_role_is_required()
    {
        $data = $this->validFields(['role' => null]);

        $employee = $this->createEmployee();

        $response = $this->submit($employee, $data);

        $response->assertSessionHasErrors('role');
    }

    private function submit(DealerEmployee $employee, array $data): TestResponse
    {
        $admin = $this->createSuperUser();

        $url = $this->url($employee);

        return $this->actingAs($admin)->put($url, $data);
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

    private function url(DealerEmployee $employee): string
    {
        return route('admin.dealers.employees.update', [
            'dealer' => $employee->dealer,
            'employee' => $employee,
        ]);
    }

    private function createEmployee(): DealerEmployee
    {
        $dealer = factory(Dealer::class)->create();

        $employee = factory(DealerEmployee::class)->make();

        $employee->dealer()->associate($dealer)->save();

        return $employee;
    }
}
