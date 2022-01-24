<?php

namespace Tests\Unit\Services\Search\Page;

use App\Models\Article;
use App\Models\CaravanRange;
use App\Models\CaravanStockItem;
use App\Models\Dealer;
use App\Models\Event;
use App\Models\Manufacturer;
use App\Models\MotorhomeRange;
use App\Models\MotorhomeStockItem;
use App\Models\Page;
use App\Models\SpecialOffer;
use App\Models\Vacancy;
use App\Models\Video;
use App\Services\Search\Page\DataProviders\ArticleDataProvider;
use App\Services\Search\Page\DataProviders\CaravanRangeDataProvider;
use App\Services\Search\Page\DataProviders\CaravanStockItemDataProvider;
use App\Services\Search\Page\DataProviders\DealerDataProvider;
use App\Services\Search\Page\DataProviders\EventDataProvider;
use App\Services\Search\Page\DataProviders\ManufacturerDataProvider;
use App\Services\Search\Page\DataProviders\MotorhomeRangeDataProvider;
use App\Services\Search\Page\DataProviders\MotorhomeStockItemDataProvider;
use App\Services\Search\Page\DataProviders\PageDataProvider;
use App\Services\Search\Page\DataProviders\SpecialOfferDataProvider;
use App\Services\Search\Page\DataProviders\VacancyDataProvider;
use App\Services\Search\Page\DataProviders\VideoDataProvider;
use App\Services\Search\Page\DataProviderFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DataProviderFactoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_returns_correct_provider_for_page_without_pageable()
    {
        $page = factory(Page::class)->create();

        $dataProvider = DataProviderFactory::getDataProvider($page);

        $this->assertInstanceOf(PageDataProvider::class, $dataProvider);
    }

    /**
     * @dataProvider provide_data_provider_data
     */
    public function test_returns_correct_provider_for_page(
        string $pageableClassName,
        string $expectedDataProviderClassName
    ) {
        $site = $this->createSite();
        $pageableClass = factory($pageableClassName)->create();
        $page = $this->createPageForPageable($pageableClass, $site);

        $dataProvider = DataProviderFactory::getDataProvider($page);

        $this->assertInstanceOf($expectedDataProviderClassName, $dataProvider);
    }

    public function provide_data_provider_data(): array
    {
        return [
            [
                Article::class,
                ArticleDataProvider::class,
            ],
            [
                CaravanRange::class,
                CaravanRangeDataProvider::class,
            ],
            [
                CaravanStockItem::class,
                CaravanStockItemDataProvider::class,
            ],
            [
                Dealer::class,
                DealerDataProvider::class,
            ],
            [
                Event::class,
                EventDataProvider::class,
            ],
            [
                Manufacturer::class,
                ManufacturerDataProvider::class,
            ],
            [
                MotorhomeRange::class,
                MotorhomeRangeDataProvider::class,
            ],
            [
                MotorhomeStockItem::class,
                MotorhomeStockItemDataProvider::class,
            ],
            [
                SpecialOffer::class,
                SpecialOfferDataProvider::class,
            ],
            [
                Vacancy::class,
                VacancyDataProvider::class,
            ],
            [
                Video::class,
                VideoDataProvider::class,
            ],
        ];
    }
}