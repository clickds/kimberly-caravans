<?php

namespace Tests\Feature\Admin\Dealer\Employees;

use App\Models\DealerEmployee;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;
use App\Models\Dealer;

class DestroyTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function test_successful()
    {
        $employee = $this->createEmployee();

        $employeeId = $employee->id;

        $response = $this->submit($employee);

        $response->assertRedirect(route('admin.dealers.employees.index', $employee->dealer));

        $this->assertDatabaseMissing('dealer_employees', ['id' => $employeeId]);
    }

    private function submit(DealerEmployee $employee): TestResponse
    {
        $admin = $this->createSuperUser();

        $url = $this->url($employee);

        return $this->actingAs($admin)->delete($url);
    }

    private function url(DealerEmployee $employee): string
    {
        return route('admin.dealers.employees.destroy', [
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
