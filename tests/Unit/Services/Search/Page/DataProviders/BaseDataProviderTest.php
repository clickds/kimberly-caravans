<?php

namespace Tests\Unit\Services\Search\Page\DataProviders;

use App\Models\Page;
use App\Services\Search\Page\DataProviders\BaseDataProvider;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

abstract class BaseDataProviderTest extends TestCase
{
    use RefreshDatabase;

    public function getExpectedData(Page $page, string $type, string $content, array $overrides = []): array
    {
        return array_merge([
            BaseDataProvider::KEY_SITE_ID => $page->site_id,
            BaseDataProvider::KEY_RELATIVE_URL => pageLink($page, false),
            BaseDataProvider::KEY_NAME => $page->name,
            BaseDataProvider::KEY_TYPE => $type,
            BaseDataProvider::KEY_CONTENT => $content,
        ], $overrides);
    }
}