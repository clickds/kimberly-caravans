<?php

namespace Tests\Unit\Services\Search\Page\DataProviders;

use App\Models\Article;
use App\Services\Search\Page\DataProviders\ArticleDataProvider;

class ArticleDataProviderTest extends BaseDataProviderTest
{
    public function test_returns_expected_data()
    {
        $site = $this->createSite();
        $article = factory(Article::class)->create();
        $page = $this->createPageForPageable($article, $site);

        $dataProvider = new ArticleDataProvider($page);

        $this->assertEquals(
            $this->getExpectedData($page, 'Article', $article->excerpt),
            $dataProvider->generateSiteSearchData()
        );
    }
}