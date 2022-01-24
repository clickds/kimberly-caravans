<?php

namespace Tests\Feature\Admin\Layouts;

use App\Models\Layout;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class UpdateTest extends TestCase
{
    use RefreshDatabase;

    public function test_successful()
    {
        $layout = $this->create_Layout();

        $updateData = [
            'code' => 'BB',
            'name' => 'Another layout name',
        ];

        $response = $this->submit($layout, $updateData);

        $response->assertRedirect(route('admin.layouts.index'));

        $this->assertDatabaseHas('layouts', $updateData);
    }

    public function test_requires_a_code()
    {
        $layout = $this->create_Layout();

        $updateData = [
            'code' => '',
            'name' => 'A layout name',
        ];

        $response = $this->submit($layout, $updateData);

        $response->assertSessionHasErrors('code');
    }

    public function test_requires_a_name()
    {
        $layout = $this->create_Layout();

        $updateData = [
            'code' => 'AA',
            'name' => '',
        ];

        $response = $this->submit($layout, $updateData);

        $response->assertSessionHasErrors('name');
    }

    public function test_code_is_required_to_be_unique()
    {
        Layout::create([
            'code' => 'BB',
            'name' => 'A layout name'
        ]);

        $layout = $this->create_Layout();

        $updateData = [
            'code' => 'BB',
            'name' => 'A test name',
        ];

        $response = $this->submit($layout, $updateData);

        $response->assertSessionHasErrors('code');
    }


    private function create_Layout(): Layout
    {
        return Layout::create(['code' => 'AA', 'name' => 'A layout name']);
    }

    private function url(Layout $layout): string
    {
        return route('admin.layouts.update', ['layout' => $layout]);
    }

    private function submit(Layout $layout, $data = []): TestResponse
    {
        $admin = $this->createSuperUser();

        $url = $this->url($layout);

        return $this->actingAs($admin)->put($url, $data);
    }
}
