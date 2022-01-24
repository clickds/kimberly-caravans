<?php

namespace Tests\Feature\Admin\Layouts;

use App\Models\Layout;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class StoreTest extends TestCase
{
    use RefreshDatabase;

    public function test_successful()
    {
        $data = [
            'code' => 'AB',
            'name' => 'Some layout name',
        ];

        $response = $this->submit($data);

        $response->assertRedirect(route('admin.layouts.index'));

        $this->assertDatabaseHas('layouts', $data);
    }

    public function test_requires_a_code()
    {
        $data = [
            'name' => 'A test name',
            'code' => '',
        ];

        $response = $this->submit($data);

        $response->assertSessionHasErrors('code');
    }

    public function test_code_is_required_to_be_unique()
    {
        $data = [
            'code' => 'AB',
            'name' => 'A test name',
        ];

        Layout::create($data);

        $duplicateCodeData = [
            'code' => 'AB',
            'name' => 'Another test name',
        ];

        $response = $this->submit($duplicateCodeData);

        $response->assertSessionHasErrors('code');
    }

    public function test_requires_a_name()
    {
        $data = [
            'code' => 'AA',
            'name' => '',
        ];

        $response = $this->submit($data);

        $response->assertSessionHasErrors('name');
    }

    private function url(): string
    {
        return route('admin.layouts.store');
    }

    private function submit($data = []): TestResponse
    {
        $admin = $this->createSuperUser();
        $url = $this->url();

        return $this->actingAs($admin)->post($url, $data);
    }
}
