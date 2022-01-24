<?php

namespace Tests\Unit\Services\Search\Page\DataProviders;

use App\Models\Area;
use App\Models\Page;
use App\Models\Panel;
use App\Services\Search\Page\DataProviders\PageDataProvider;

class PageDataProviderTest extends BaseDataProviderTest
{
    public function test_returns_expected_data()
    {
        $page = $this->createPageWithAnAreaAndPanel();

        $dataProvider = new PageDataProvider($page);

        $this->assertEquals(
            $this->getExpectedData($page, 'Page', $this->getFirstEligablePanelContent($page)),
            $dataProvider->generateSiteSearchData()
        );
    }

    private function createPageWithAnAreaAndPanel(): Page
    {
        $page = factory(Page::class)->create();
        $area = factory(Area::class)->create(['page_id' => $page->id]);
        factory(Panel::class)->create(['area_id' => $area->id]);

        return $page;
    }

    private function getFirstEligablePanelContent(Page $page): string
    {
        $firstEligableArea = $page->areas()
        ->published()
        ->live()
        ->notExpired()
        ->orderBy('position')
        ->first();

        if (is_null($firstEligableArea)) {
            return '';
        }

        $eligablePanel = $firstEligableArea->panels()
            ->published()
            ->live()
            ->notExpired()
            ->orderBy('position')
            ->whereNotNull('content')
            ->first();

        if (is_null($eligablePanel)) {
            return '';
        }

        return strip_tags($eligablePanel->content);
    }
}