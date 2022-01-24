<?php

namespace Tests\Unit\Services\Search\Page\DataProviders;

use App\Models\Vacancy;
use App\Services\Search\Page\DataProviders\VacancyDataProvider;

class VacancyDataProviderTest extends BaseDataProviderTest
{
    public function test_returns_expected_data()
    {
        $site = $this->createSite();
        $vacancy = factory(Vacancy::class)->create();
        $page = $this->createPageForPageable($vacancy, $site);

        $dataProvider = new VacancyDataProvider($page);

        $this->assertEquals(
            $this->getExpectedData($page, 'Job Vacancy', strip_tags($vacancy->description)),
            $dataProvider->generateSiteSearchData()
        );
    }
}