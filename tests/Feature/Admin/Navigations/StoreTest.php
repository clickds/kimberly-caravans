<?php

namespace Tests\Feature\Admin\Navigations;

use App\Models\Navigation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;
use App\Models\Site;

class StoreTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    public function test_successful()
    {
        $this->createSite();

        $data = $this->validNavigationData();

        $response = $this->submit($data);

        $response->assertRedirect(route('admin.navigations.index'));

        $this->assertDatabaseHas('navigations', $data);
    }

    /**
     * @dataProvider requiredValidationProvider
     */
    public function test_required_validation(string $inputName): void
    {
        $this->createSite();

        $data = $this->validNavigationData([
            $inputName => null,
        ]);

        $response = $this->submit($data);

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

        $existingNavigation = factory(Navigation::class)->create();

        $newNavigationData = $this->validNavigationData([
            'type' => $existingNavigation->type,
            'site_id' => $site->id,
        ]);

        $response = $this->submit($newNavigationData);

        $response->assertSessionHasErrors('type');
    }

    public function test_type_must_be_unique_for_site()
    {
        $site = $this->createSite();

        $existingNavigation = factory(Navigation::class)->create();

        $newNavigationData = $this->validNavigationData([
            'name' => $existingNavigation->name,
            'site_id' => $site->id,
        ]);

        $response = $this->submit($newNavigationData);

        $response->assertSessionHasErrors('name');
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

    private function submit($data = []): TestResponse
    {
        $admin = $this->createSuperUser();

        $url = $this->url();

        return $this->actingAs($admin)->post($url, $data);
    }

    private function url(): string
    {
        return route('admin.navigations.store');
    }
}
