<?php

namespace Tests\Feature\Admin\Navigation\NavigationItems\Hierarchy;

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

    public function test_requires_navigation_items_key(): void
    {
        $navigation = $this->createNavigationWithItems();

        $response = $this->submit($navigation);

        $response->assertSessionHasErrors('navigation_items');
    }

    public function test_navigation_item_id_must_exist(): void
    {
        $navigation = $this->createNavigationWithItems();

        $navigationItemsHierarchy = $this->createNavigationItemsHierarchyData($navigation);

        $navigationItemsHierarchy[0]['navigationItemId'] = 50; // Navigation item id that doesn't exist

        $response = $this->submit($navigation, ['navigation_items' => json_encode($navigationItemsHierarchy)]);

        $response->assertSessionHasErrors('navigation_items.0.navigationItemId');
    }

    public function test_child_navigation_item_id_must_exist(): void
    {
        $navigation = $this->createNavigationWithItems();

        $navigationItemsHierarchy = $this->createNavigationItemsHierarchyData($navigation);
        $navigationItemsHierarchy[0]['children'] = $navigationItemsHierarchy[2];
        $navigationItemsHierarchy[0]['children'][0]['navigationItemId'] = 50; // Navigation item id that doesn't exist
        unset($navigationItemsHierarchy[2]);

        $response = $this->submit($navigation, ['navigation_items' => json_encode($navigationItemsHierarchy)]);

        $response->assertSessionHasErrors('navigation_items.0.children.0.navigationItemId');
    }

    public function test_cant_have_nested_children(): void
    {
        $navigation = $this->createNavigationWithItems();

        $navigationItemsHierarchy = $this->createNavigationItemsHierarchyData($navigation);
        $navigationItemsHierarchy[0]['children'][] = $navigationItemsHierarchy[2];
        $navigationItemsHierarchy[0]['children'][0]['children'][] = $navigationItemsHierarchy[3];

        unset($navigationItemsHierarchy[2]);
        unset($navigationItemsHierarchy[3]);

        $response = $this->submit($navigation, ['navigation_items' => json_encode($navigationItemsHierarchy)]);

        $response->assertSessionHasErrors('navigation_items.0.children.0');
    }

    public function test_sets_display_order_correctly(): void
    {
        $navigation = $this->createNavigationWithItems();

        $navigationItemsHierarchy = $this->createNavigationItemsHierarchyData($navigation);

        $this->submit($navigation, ['navigation_items' => json_encode($navigationItemsHierarchy)]);

        $updatedNavigation = Navigation::where('id', $navigation->id)->firstOrFail();

        $displayOrder = 1;

        foreach ($navigationItemsHierarchy as $navigationItemDatum) {
            $navigationItem = $updatedNavigation->navigationItems()
                ->where('id', $navigationItemDatum['navigationItemId'])
                ->firstOrFail();

            $this->assertEquals($displayOrder, $navigationItem->display_order);

            $displayOrder++;
        }
    }

    public function test_sets_parent_id_correctly(): void
    {
        $navigation = $this->createNavigationWithItems();

        $navigationItemsHierarchy = $this->createNavigationItemsHierarchyData($navigation);

        $navigationItemsHierarchy[0]['children'][] = $navigationItemsHierarchy[1];
        $navigationItemsHierarchy[0]['children'][] = $navigationItemsHierarchy[2];
        unset($navigationItemsHierarchy[1]);
        unset($navigationItemsHierarchy[2]);

        $this->submit($navigation, ['navigation_items' => json_encode($navigationItemsHierarchy)]);

        $updatedNavigation = Navigation::where('id', $navigation->id)->firstOrFail();

        foreach ($navigationItemsHierarchy as $navigationItemDatum) {
            $navigationItem = $updatedNavigation->navigationItems()
                ->where('id', $navigationItemDatum['navigationItemId'])
                ->firstOrFail();

            $this->assertEquals(null, $navigationItem->parent_id);

            if (isset($navigationItemDatum['children'])) {
                foreach ($navigationItemDatum['children'] as $childItemDatum) {
                    $childNavigationItem = $updatedNavigation->navigationItems()
                        ->where('id', $childItemDatum['navigationItemId'])
                        ->firstOrFail();

                    $this->assertEquals($navigationItem->id, $childNavigationItem->parent_id);
                }
            }
        }
    }

    private function createNavigationWithItems(): Navigation
    {
        factory(Page::class)->create();

        $this->createSite();

        $navigation = factory(Navigation::class)->create();

        factory(NavigationItem::class, 4)->create(['navigation_id' => $navigation->id]);

        return $navigation;
    }

    private function submit(Navigation $navigation, $data = []): TestResponse
    {
        $admin = $this->createSuperUser();

        $url = $this->url($navigation);

        return $this->actingAs($admin)->put($url, $data);
    }

    private function url(Navigation $navigation): string
    {
        return route(
            'admin.navigations.navigation-items-hierarchy.update',
            ['navigation' => $navigation]
        );
    }

    private function createNavigationItemsHierarchyData(Navigation $navigation): array
    {
        $navigationItemsHierarchy = [];

        $navigation->navigationItems->map(function (NavigationItem $navigationItem) use (&$navigationItemsHierarchy) {
            $navigationItemsHierarchy[] = ['navigationItemId' => $navigationItem->id];
        });

        return $navigationItemsHierarchy;
    }
}
