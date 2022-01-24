<?php

namespace App\Services\Search\Page\DataProviders;

interface SiteSearchDataProvider
{
    public const VALID_TYPES = [
        ArticleDataProvider::TYPE,
        CaravanRangeDataProvider::TYPE,
        CaravanStockItemDataProvider::TYPE,
        DealerDataProvider::TYPE,
        EventDataProvider::TYPE,
        ManufacturerDataProvider::TYPE,
        MotorhomeRangeDataProvider::TYPE,
        MotorhomeStockItemDataProvider::TYPE,
        PageDataProvider::TYPE,
        SpecialOfferDataProvider::TYPE,
        VacancyDataProvider::TYPE,
        VideoDataProvider::TYPE,
    ];

    public function generateSiteSearchData(): array;
}
