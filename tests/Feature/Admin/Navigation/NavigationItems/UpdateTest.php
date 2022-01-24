<?php

namespace Tests\Feature\Admin\Navigation\NavigationItems;

use App\Models\Navigation;
use App\Models\NavigationItem;
use App\Models\Page;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class UpdateTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    public function test_successful()
    {
        $navigationItem = $this->createNavigationItem();

        $data = $this->validNavigationItemData();

        $response = $this->submit($navigationItem->navigation, $navigationItem, $data);

        $response->assertRedirect(
            route('admin.navigations.navigation-items.index', ['navigation' => $navigationItem->navigation])
        );

        $this->assertDatabaseHas('navigation_items', $data);
    }

    /**
     * @dataProvider requiredValidationProvider
     */
    public function test_required_validation(string $inputName): void
    {
        $navigationItem = $this->createNavigationItem();

        $data = $this->validNavigationItemData([
            $inputName => null,
        ]);

        $response = $this->submit($navigationItem->navigation, $navigationItem, $data);

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
        $navigationItem = $this->createNavigationItem();

        $navigationItemData = $this->validNavigationItemData();

        $erroneousNavigationItemData = array_merge(
            $navigationItemData,
            [
                'page_id' => null,
                'external_url' => null,
            ]
        );

        $response = $this->submit($navigationItem->navigation, $navigationItem, $erroneousNavigationItemData);

        $response->assertSessionHasErrors(['page_id', 'external_url']);

        $navigationItemDataWithoutPageId = array_merge($navigationItemData, ['page_id' => null]);

        $this->submit($navigationItem->navigation, $navigationItem, $navigationItemDataWithoutPageId);

        $this->assertDatabaseHas('navigation_items', $navigationItemDataWithoutPageId);

        $navigationItemDataWithoutExternalUrl = array_merge($navigationItemData, ['external_url' => null]);

        $this->submit($navigationItem->navigation, $navigationItem, $navigationItemDataWithoutExternalUrl);

        $this->assertDatabaseHas('navigation_items', $navigationItemDataWithoutExternalUrl);
    }

    private function createNavigationItem(): NavigationItem
    {
        factory(Page::class)->create();

        $this->createSite();

        $navigation = factory(Navigation::class)->create();

        return factory(NavigationItem::class)->create(['navigation_id' => $navigation->id]);
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

    private function submit(Navigation $navigation, NavigationItem $navigationItem, $data = []): TestResponse
    {
        $admin = $this->createSuperUser();

        $url = $this->url($navigation, $navigationItem);

        return $this->actingAs($admin)->put($url, $data);
    }

    private function url(Navigation $navigation, NavigationItem $navigationItem): string
    {
        return route(
            'admin.navigations.navigation-items.update',
            [
                'navigation' => $navigation,
                'navigation_item' => $navigationItem
            ]
        );
    }
}
