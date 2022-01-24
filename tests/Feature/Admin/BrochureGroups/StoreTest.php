<?php

namespace Tests\Feature\Admin\BrochureGroups;

use App\Models\BrochureGroup;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class StoreTest extends TestCase
{
    use RefreshDatabase;

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
        ];
    }

    /**
     * @dataProvider uniqueProvider
     */
    public function test_unique_validation(string $inputName): void
    {
        $otherBrochureGroup = $this->createBrochureGroup();
        $data = $this->validData([
            $inputName => $otherBrochureGroup->$inputName,
        ]);

        $response = $this->submit($data);

        $response->assertSessionHasErrors($inputName);
    }

    public function uniqueProvider(): array
    {
        return [
            ['name'],
        ];
    }

    public function test_successful(): void
    {
        $data = $this->validData();

        $response = $this->submit($data);

        $response->assertRedirect(route('admin.brochure-groups.index'));
        $this->assertDatabaseHas('brochure_groups', $data);
    }

    private function submit($data): TestResponse
    {
        $user = $this->createSuperUser();
        $url = route('admin.brochure-groups.store');

        return $this->actingAs($user)->post($url, $data);
    }

    private function validData(array $overrides = []): array
    {
        $defaults = [
            'name' => 'some name',
            'position' => 4,
        ];

        return array_merge($defaults, $overrides);
    }

    private function createBrochureGroup(array $attributes = []): BrochureGroup
    {
        return factory(BrochureGroup::class)->create($attributes);
    }
}
