<?php

namespace Tests\Feature\Admin\Navigation\NavigationItems;

use App\Models\Navigation;
use App\Models\NavigationItem;
use App\Models\Page;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class DestroyTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    public function test_successful()
    {
        $navigationItem = $this->createNavigationItem();

        $response = $this->submit($navigationItem->navigation, $navigationItem);

        $response->assertRedirect(
            route('admin.navigations.navigation-items.index', ['navigation' => $navigationItem->navigation])
        );

        $this->assertDatabaseMissing('navigation_items', ['id' => $navigationItem->id]);
    }

    private function createNavigationItem(): NavigationItem
    {
        factory(Page::class)->create();

        $this->createSite();

        $navigation = factory(Navigation::class)->create();

        return factory(NavigationItem::class)->create(['navigation_id' => $navigation->id]);
    }

    private function submit(Navigation $navigation, NavigationItem $navigationItem): TestResponse
    {
        $admin = $this->createSuperUser();

        $url = $this->url($navigation, $navigationItem);

        return $this->actingAs($admin)->delete($url);
    }

    private function url(Navigation $navigation, NavigationItem $navigationItem): string
    {
        return route(
            'admin.navigations.navigation-items.destroy',
            [
                'navigation' => $navigation,
                'navigation_item' => $navigationItem
            ]
        );
    }
}
