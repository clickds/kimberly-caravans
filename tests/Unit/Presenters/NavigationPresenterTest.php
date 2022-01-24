<?php

namespace Tests\Unit\Presenters;

use App\Models\Navigation;
use App\Models\NavigationItem;
use App\Models\Page;
use App\Presenters\NavigationItemPresenter;
use App\Presenters\NavigationPresenter;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NavigationPresenterTest extends TestCase
{
    use RefreshDatabase;

    public function test_new_caravan_page_when_navigation_has_no_navigation_items(): void
    {
        $navigation = new Navigation();
        $presenter = $this->buildPresenter($navigation);

        $this->assertNull($presenter->newCaravansPage());
    }

    public function test_new_caravan_page_when_navigation_has_caravan_page(): void
    {
        $navigation = new Navigation();
        $externalUrl = $this->buildExternalUrlNavigationItemPresenter();
        $page = $this->buildPageNavigationItemPresenter(Page::TEMPLATE_NEW_CARAVANS);
        $items = collect([$externalUrl, $page]);
        $navigation->setRelation('navigationItems', $items);
        $presenter = $this->buildPresenter($navigation);

        $this->assertEquals($page, $presenter->newCaravansPage());
    }

    public function test_new_caravan_page_when_navigation_does_not_have_caravan_page(): void
    {
        $navigation = new Navigation();
        $externalUrl = $this->buildExternalUrlNavigationItemPresenter();
        $items = collect([$externalUrl]);
        $navigation->setRelation('navigationItems', $items);
        $presenter = $this->buildPresenter($navigation);

        $this->assertNull($presenter->newCaravansPage());
    }

    public function test_new_motorhome_page_when_navigation_has_no_navigation_items(): void
    {
        $navigation = new Navigation();
        $presenter = $this->buildPresenter($navigation);

        $this->assertNull($presenter->newMotorhomesPage());
    }

    public function test_new_motorhome_page_when_navigation_has_caravan_page(): void
    {
        $navigation = new Navigation();
        $externalUrl = $this->buildExternalUrlNavigationItemPresenter();
        $page = $this->buildPageNavigationItemPresenter(Page::TEMPLATE_NEW_MOTORHOMES);
        $items = collect([$externalUrl, $page]);
        $navigation->setRelation('navigationItems', $items);
        $presenter = $this->buildPresenter($navigation);

        $this->assertEquals($page, $presenter->newMotorhomesPage());
    }

    public function test_new_motorhome_page_when_navigation_does_not_have_caravan_page(): void
    {
        $navigation = new Navigation();
        $externalUrl = $this->buildExternalUrlNavigationItemPresenter();
        $items = collect([$externalUrl]);
        $navigation->setRelation('navigationItems', $items);
        $presenter = $this->buildPresenter($navigation);

        $this->assertNull($presenter->newMotorhomesPage());
    }
    private function buildPresenter(Navigation $navigation): NavigationPresenter
    {
        $presenter = new NavigationPresenter();
        $presenter->setWrappedObject($navigation);
        return $presenter;
    }

    private function buildPageNavigationItemPresenter(string $template): NavigationItemPresenter
    {
        $page = new Page([
            'template' => $template,
        ]);
        $navItem = new NavigationItem();
        $navItem->setRelation('page', $page);
        return $this->buildNavItemPresenter($navItem);
    }

    public function buildExternalUrlNavigationItemPresenter(): NavigationItemPresenter
    {
        $navItem = new NavigationItem([
            'external_url' => 'https://www.google.co.uk',
        ]);
        return $this->buildNavItemPresenter($navItem);
    }

    private function buildNavItemPresenter(NavigationItem $navigationItem): NavigationItemPresenter
    {
        $presenter = new NavigationItemPresenter();
        $presenter->setWrappedObject($navigationItem);
        return $presenter;
    }
}
