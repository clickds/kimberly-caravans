<?php

namespace Tests\Unit\Presenters\Page;

use App\Models\ImageBanner;
use App\Models\Page;
use App\Models\VideoBanner;
use App\Presenters\Page\BasePagePresenter;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BasePagePresenterTest extends TestCase
{
    use RefreshDatabase;

    public function test_get_video_banner(): void
    {
        $videoBanner = factory(VideoBanner::class)->create();
        $page = factory(Page::class)->create([
            'video_banner_id' => $videoBanner->id,
        ]);
        $presenter = $this->buildPresenter($page);

        $this->assertEquals($videoBanner->id, $presenter->getVideoBanner()->id);
    }

    public function test_get_image_banners(): void
    {
        $imageBanner = factory(ImageBanner::class)->create();
        $page = factory(Page::class)->create();
        $page->imageBanners()->attach($imageBanner);
        $presenter = $this->buildPresenter($page);

        $this->assertTrue($presenter->getImageBanners()->contains(function ($banner) use ($imageBanner) {
            return $banner->id === $imageBanner->id;
        }));
    }

    public function test_link_when_no_parent(): void
    {
        $page = factory(Page::class)->create();
        $presenter = $this->buildPresenter($page);

        $expectedUrl = route('site', [
            'page' => $page->slug,
        ]);
        $this->assertEquals($expectedUrl, $presenter->link());
    }

    public function test_link_when_parent(): void
    {
        $parentPage = factory(Page::class)->create();
        $page = factory(Page::class)->create([
            'parent_id' => $parentPage->id,
        ]);
        $presenter = $this->buildPresenter($page);
        $expectedUrl = route('site', [
            'page' => $parentPage->slug,
            'childPage' => $page->slug,
        ]);
        $this->assertEquals($expectedUrl, $presenter->link());
    }

    private function buildPresenter(Page $page): BasePagePresenter
    {
        $presenter = new BasePagePresenter();
        $presenter->setWrappedObject($page);
        return $presenter;
    }
}
