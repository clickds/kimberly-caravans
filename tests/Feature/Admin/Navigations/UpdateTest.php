<?php

namespace Tests\Feature\Admin\Navigations;

use App\Models\Navigation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;
use App\Models\Site;

class UpdateTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    public function test_successful()
    {
        $this->createSite();

        $navigation = factory(Navigation::class)->create();

        $data = $this->validNavigationData();

        $response = $this->submit($navigation, $data);

        $response->assertRedirect(route('admin.navigations.index'));

        $this->assertDatabaseHas('navigations', $data);
    }

    /**
     * @dataProvider requiredValidationProvider
     */
    public function test_required_validation(string $inputName): void
    {
        $this->createSite();

        $navigation = factory(Navigation::class)->create();

        $data = $this->validNavigationData([
            $inputName => null,
        ]);

        $response = $this->submit($navigation, $data);

        $response->assertSessionHasErrors($inputName);
    }

    public function requiredValidationProvider(): array
    {
        return [
            ['name'],
            ['site_id'],
            ['type'],
        ];
    }

    public function test_name_must_be_unique_for_site(): void
    {
        $site = $this->createSite();

        $existingNavigation = factory(Navigation::class)->create([
            'site_id' => $site->id,
            'type' => array_keys(Navigation::NAVIGATION_TYPES)[0],
            'name' => $this->faker->name,
        ]);

        $navigationToUpdateData = [
            'site_id' => $site->id,
            'type' => array_keys(Navigation::NAVIGATION_TYPES)[1],
            'name' => $this->faker->name,
        ];

        $navigationToUpdate = factory(Navigation::class)->create($navigationToUpdateData);

        $navigationToUpdateData['name'] = $existingNavigation->name;

        $response = $this->submit($navigationToUpdate, $navigationToUpdateData);

        $response->assertSessionHasErrors('name');
    }

    public function test_type_must_be_unique_for_site(): void
    {
        $site = $this->createSite();

        $existingNavigation = factory(Navigation::class)->create([
            'site_id' => $site->id,
            'type' => array_keys(Navigation::NAVIGATION_TYPES)[0],
            'name' => $this->faker->name,
        ]);

        $navigationToUpdateData = [
            'site_id' => $site->id,
            'type' => array_keys(Navigation::NAVIGATION_TYPES)[1],
            'name' => $this->faker->name,
        ];

        $navigationToUpdate = factory(Navigation::class)->create($navigationToUpdateData);

        $navigationToUpdateData['type'] = $existingNavigation->type;

        $response = $this->submit($navigationToUpdate, $navigationToUpdateData);

        $response->assertSessionHasErrors('type');
    }

    public function validNavigationData(array $overrides = []): array
    {
        return array_merge(
            [
                'name' => $this->faker->name,
                'site_id' => Site::select('id')->firstOrFail()->id,
                'type' => $this->faker->randomElement(array_keys(Navigation::NAVIGATION_TYPES)),
            ],
            $overrides
        );
    }

    private function submit(Navigation $navigation, $data = []): TestResponse
    {
        $admin = $this->createSuperUser();

        $url = $this->url($navigation);

        return $this->actingAs($admin)->put($url, $data);
    }

    private function url(Navigation $navigation): string
    {
        return route('admin.navigations.update', ['navigation' => $navigation]);
    }
}
