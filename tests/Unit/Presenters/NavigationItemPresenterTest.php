<?php

namespace Tests\Unit\Presenters;

use App\Models\NavigationItem;
use App\Models\Page;
use App\Presenters\NavigationItemPresenter;
use App\Presenters\Page\BasePagePresenter;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NavigationItemPresenterTest extends TestCase
{
    use RefreshDatabase;

    public function test_link_target_is_blank_when_external_url(): void
    {
        $navItem = new NavigationItem([
            'external_url' => 'https://www.google.co.uk'
        ]);
        $presenter = $this->buildPresenter($navItem);

        $this->assertEquals('_blank', $presenter->linkTarget());
    }

    public function test_link_target_is_self_when_not_external_url(): void
    {
        $navItem = new NavigationItem([
            'external_url' => null,
        ]);
        $presenter = $this->buildPresenter($navItem);

        $this->assertEquals('_self', $presenter->linkTarget());
    }

    public function test_link_url_when_external_url(): void
    {
        $navItem = new NavigationItem([
            'external_url' => 'https://www.google.co.uk'
        ]);
        $presenter = $this->buildPresenter($navItem);

        $this->assertEquals($navItem->external_url, $presenter->linkUrl());
    }

    public function test_link_url_when_page_already_presented(): void
    {
        $page = factory(Page::class)->create([
            'name' => 'test',
        ]);
        $pagePresenter = $this->buildPagePresenter($page);
        $navItem = new NavigationItem([
            'external_url' => null,
        ]);
        $navItem->setRelation('page', $pagePresenter);
        $presenter = $this->buildPresenter($navItem);

        $expectedUrl = route('site', [
            'page' => $page->slug,
        ], false);
        $this->assertEquals($expectedUrl, $presenter->linkUrl());
    }

    public function test_link_url_when_page_not_presented(): void
    {
        $page = factory(Page::class)->create([
            'name' => 'test',
        ]);
        $navItem = new NavigationItem([
            'external_url' => null,
        ]);
        $navItem->setRelation('page', $page);
        $presenter = $this->buildPresenter($navItem);

        $expectedUrl = route('site', [
            'page' => $page->slug,
        ], false);
        $this->assertEquals($expectedUrl, $presenter->linkUrl());
    }

    public function test_query_params_appended_to_page_url_if_present(): void
    {
        $page = factory(Page::class)->create([
            'name' => 'test',
        ]);
        $pagePresenter = $this->buildPagePresenter($page);
        $navItem = new NavigationItem([
            'external_url' => null,
            'query_params' => '?test=true',
        ]);
        $navItem->setRelation('page', $pagePresenter);
        $presenter = $this->buildPresenter($navItem);

        $expectedUrl = route('site', [
            'page' => $page->slug,
        ], false);

        $expectedUrl .= $navItem->query_parameters;

        $this->assertEquals($expectedUrl, $presenter->linkUrl());
    }

    private function buildPagePresenter(Page $page): BasePagePresenter
    {
        $presenter = new BasePagePresenter();
        $presenter->setWrappedObject($page);
        return $presenter;
    }

    private function buildPresenter(NavigationItem $navigationItem): NavigationItemPresenter
    {
        $presenter = new NavigationItemPresenter();
        $presenter->setWrappedObject($navigationItem);
        return $presenter;
    }
}
