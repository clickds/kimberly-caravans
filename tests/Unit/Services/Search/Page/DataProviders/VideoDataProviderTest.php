<?php

namespace Tests\Unit\Services\Search\Page\DataProviders;

use App\Models\Video;
use App\Services\Search\Page\DataProviders\VideoDataProvider;

class VideoDataProviderTest extends BaseDataProviderTest
{
    public function test_returns_expected_data()
    {
        $site = $this->createSite();
        $video = factory(Video::class)->create();
        $page = $this->createPageForPageable($video, $site);

        $dataProvider = new VideoDataProvider($page);

        $this->assertEquals(
            $this->getExpectedData($page, 'Video', $video->excerpt),
            $dataProvider->generateSiteSearchData()
        );
    }
}