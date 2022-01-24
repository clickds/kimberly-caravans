<?php

namespace Tests\Feature\Admin\BedDescriptions;

use App\Models\BedDescription;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class StoreTest extends TestCase
{
    use RefreshDatabase;

    public function test_name_is_required()
    {
        $data = $this->validData([
            'name' => null,
        ]);

        $response = $this->submit($data);

        $response->assertSessionHasErrors('name');
    }

    public function test_name_is_unique()
    {
        $bedDescription = factory(BedDescription::class)->create();
        $data = $this->validData([
            'name' => $bedDescription->name,
        ]);

        $response = $this->submit($data);

        $response->assertSessionHasErrors('name');
    }

    public function test_successfully_creates_bed_description()
    {
        $data = $this->validData();

        $response = $this->submit($data);

        $response->assertRedirect(route('admin.bed-descriptions.index'));
        $this->assertDatabaseHas('bed_descriptions', $data);
    }

    private function validData(array $overrides = []): array
    {
        $defaults = [
            'name' => 'some name',
        ];

        return array_merge($defaults, $overrides);
    }

    private function submit(array $data): TestResponse
    {
        $user = $this->createSuperUser();
        $url = $this->url();

        return $this->actingAs($user)->post($url, $data);
    }

    private function url(): string
    {
        return route('admin.bed-descriptions.store');
    }
}
