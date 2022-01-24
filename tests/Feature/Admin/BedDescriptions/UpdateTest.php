<?php

namespace Tests\Feature\Admin\BedDescriptions;

use App\Models\BedDescription;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class UpdateTest extends TestCase
{
    use RefreshDatabase;

    public function test_name_is_required()
    {
        $bedDescription = $this->createBedDescription();
        $data = $this->validData([
            'name' => null,
        ]);

        $response = $this->submit($bedDescription, $data);

        $response->assertSessionHasErrors('name');
    }

    public function test_name_is_unique()
    {
        $bedDescription = $this->createBedDescription();
        $otherBedDescription = $this->createBedDescription();
        $data = $this->validData([
            'name' => $otherBedDescription->name,
        ]);

        $response = $this->submit($bedDescription, $data);

        $response->assertSessionHasErrors('name');
    }

    public function test_successfully_updates_bed_description()
    {
        $bedDescription = $this->createBedDescription();
        $data = $this->validData();

        $this->withoutExceptionHandling();
        $response = $this->submit($bedDescription, $data);

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

    private function submit(BedDescription $bedDescription, array $data): TestResponse
    {
        $user = $this->createSuperUser();
        $url = $this->url($bedDescription);

        return $this->actingAs($user)->put($url, $data);
    }

    private function createBedDescription(array $attributes = [])
    {
        return factory(BedDescription::class)->create($attributes);
    }

    private function url(BedDescription $bedDescription): string
    {
        return route('admin.bed-descriptions.update', $bedDescription);
    }
}
