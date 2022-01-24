<?php

namespace Tests\Feature\Admin\Navigation\NavigationItems;

use App\Models\Navigation;
use App\Models\NavigationItem;
use App\Models\Page;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class StoreTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    public function test_successful()
    {
        $this->createPage();

        $navigation = $this->createNavigation();

        $data = $this->validNavigationItemData();

        $response = $this->submit($navigation, $data);

        $response->assertRedirect(
            route('admin.navigations.navigation-items.index', ['navigation' => $navigation])
        );

        $this->assertDatabaseHas('navigation_items', $data);
    }

    /**
     * @dataProvider requiredValidationProvider
     */
    public function test_required_validation(string $inputName): void
    {
        $this->createPage();

        $navigation = $this->createNavigation();

        $data = $this->validNavigationItemData([
            $inputName => null,
        ]);

        $response = $this->submit($navigation, $data);

        $response->assertSessionHasErrors($inputName);
    }

    public function requiredValidationProvider(): array
    {
        return [
            ['name'],
            ['background_colour'],
        ];
    }

    public function test_requires_either_page_id_or_external_url(): void
    {
        $this->createPage();

        $navigation = $this->createNavigation();

        $navigationItemData = $this->validNavigationItemData();

        $erroneousNavigationItemData = array_merge(
            $navigationItemData,
            [
                'page_id' => null,
                'external_url' => null,
            ]
        );

        $response = $this->submit($navigation, $erroneousNavigationItemData);

        $response->assertSessionHasErrors(['page_id', 'external_url']);

        $navigationItemDataWithoutPageId = array_merge($navigationItemData, ['page_id' => null]);

        $this->submit($navigation, $navigationItemDataWithoutPageId);

        $this->assertDatabaseHas('navigation_items', $navigationItemDataWithoutPageId);

        $navigationItemDataWithoutExternalUrl = array_merge($navigationItemData, ['external_url' => null]);

        $this->submit($navigation, $navigationItemDataWithoutExternalUrl);

        $this->assertDatabaseHas('navigation_items', $navigationItemDataWithoutExternalUrl);
    }

    private function createPage(): void
    {
        factory(Page::class)->create();
    }

    private function createNavigation(): Navigation
    {
        $this->createSite();

        return factory(Navigation::class)->create();
    }

    public function validNavigationItemData(array $overrides = []): array
    {
        return array_merge(
            [
                'name' => $this->faker->name,
                'page_id' => Page::select('id')->firstOrFail()->id,
                'query_parameters' => '?test=true',
                'external_url' => $this->faker->url,
                'background_colour' => $this->faker->randomElement(array_keys(NavigationItem::BACKGROUND_COLOURS)),
            ],
            $overrides
        );
    }

    private function submit(Navigation $navigation, $data = []): TestResponse
    {
        $admin = $this->createSuperUser();

        $url = $this->url($navigation);

        return $this->actingAs($admin)->post($url, $data);
    }

    private function url(Navigation $navigation): string
    {
        return route('admin.navigations.navigation-items.store', ['navigation' => $navigation]);
    }
}
