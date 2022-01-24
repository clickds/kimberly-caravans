<?php

namespace Tests\Unit\Services\Page;

use App\Models\Area;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\Page;
use App\Models\Panel;
use App\Models\Site;
use App\Services\Page\PageCloner;
use Illuminate\Support\Arr;

class PageClonerTest extends TestCase
{
    use WithFaker;
    use RefreshDatabase;

    public function test_clones_page()
    {
        $page = $this->createPageWithAreasAndPanels();

        $newPageData = $this->getNewPageData();

        $cloner = new PageCloner($page, $newPageData);

        $newPage = $cloner->clone();

        $this->assertEquals(
            $newPageData,
            Arr::except($newPage->getAttributes(), ['id', 'created_at', 'updated_at', 'slug'])
        );

        $pageArea = $page->areas()->firstOrFail();
        $newPageArea = $newPage->areas()->firstOrFail();

        $this->assertEquals(
            Arr::except($pageArea->getAttributes(), ['page_id', 'id', 'created_at', 'updated_at']),
            Arr::except($newPageArea->getAttributes(), ['page_id', 'id', 'created_at', 'updated_at'])
        );

        $pagePanel = $pageArea->panels()->firstOrFail();
        $newPagePanel = $newPageArea->panels()->firstOrFail();

        $this->assertEquals(
            Arr::except($pagePanel->getAttributes(), ['area_id', 'id', 'created_at', 'updated_at']),
            Arr::except($newPagePanel->getAttributes(), ['area_id', 'id', 'created_at', 'updated_at']),
        );
    }

    private function createPageWithAreasAndPanels(): Page
    {
        $page = factory(Page::class)->create();

        $area = factory(Area::class)->create(['page_id' => $page->id]);

        factory(Panel::class)->create(['area_id' => $area->id]);

        return $page;
    }

    private function getNewPageData(): array
    {
        $siteId = factory(Site::class)->state('default')->create()->id;

        return [
            'site_id' => $siteId,
            'name' => $this->faker->sentence(),
            'meta_title' => $this->faker->sentence(),
            'template' => array_keys(Page::STANDARD_TEMPLATES)[0],
            'variety' => $this->faker->randomElement(Page::VARIETIES),
            'parent_id' => null,
            'live' => true,
            'published_at' => null,
            'expired_at' => null,
        ];
    }
}
