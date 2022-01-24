<?php

namespace Tests\Unit\Presenters\Page;

use App\Models\Manufacturer;
use App\Models\Page;
use App\Presenters\Page\ManufacturerPresenter;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Tests\TestCase;

class ManufacturerPresenterTest extends TestCase
{
    use RefreshDatabase;

    public function test_manufacturer_name(): void
    {
        $manufacturer = factory(Manufacturer::class)->make();
        $page = new Page();
        $page->setRelation('pageable', $manufacturer);
        $presenter = $this->buildPresenter($page);

        $this->assertEquals($manufacturer->name, $presenter->manufacturerName());
    }

    public function test_get_logo(): void
    {
        $manufacturer = factory(Manufacturer::class)->make();
        $media = new Media();
        $manufacturer->setRelation('logo', $media);
        $page = new Page();
        $page->setRelation('pageable', $manufacturer);
        $presenter = $this->buildPresenter($page);

        $this->assertEquals($media, $presenter->getLogo());
    }

    public function test_get_media_when_page_template_caravans(): void
    {
        $manufacturer = factory(Manufacturer::class)->make();
        $caravanMedia = new Media();
        $motorhomeMedia = new Media();
        $manufacturer->setRelation('caravanImage', $caravanMedia);
        $manufacturer->setRelation('motorhomeImage', $motorhomeMedia);
        $page = new Page([
            'template' => Page::TEMPLATE_MANUFACTURER_CARAVANS,
        ]);
        $page->setRelation('pageable', $manufacturer);
        $presenter = $this->buildPresenter($page);

        $this->assertEquals($caravanMedia, $presenter->getMedia());
    }

    public function test_get_media_when_page_template_motorhomes(): void
    {
        $manufacturer = factory(Manufacturer::class)->make();
        $caravanMedia = new Media();
        $motorhomeMedia = new Media();
        $manufacturer->setRelation('caravanImage', $caravanMedia);
        $manufacturer->setRelation('motorhomeImage', $motorhomeMedia);
        $page = new Page([
            'template' => Page::TEMPLATE_MANUFACTURER_MOTORHOMES,
        ]);
        $page->setRelation('pageable', $manufacturer);
        $presenter = $this->buildPresenter($page);

        $this->assertEquals($motorhomeMedia, $presenter->getMedia());
    }

    private function buildPresenter(Page $page): ManufacturerPresenter
    {
        $presenter = new ManufacturerPresenter();
        $presenter->setWrappedObject($page);
        return $presenter;
    }
}
