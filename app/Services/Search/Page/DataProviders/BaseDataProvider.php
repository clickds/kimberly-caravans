<?php

namespace App\Services\Search\Page\DataProviders;

use App\Models\Page;

abstract class BaseDataProvider implements SiteSearchDataProvider
{
    public const KEY_SITE_ID = 'siteId';
    public const KEY_RELATIVE_URL = 'relativeUrl';
    public const KEY_NAME = 'name';
    public const KEY_TYPE = 'type';
    public const KEY_CONTENT = 'content';

    protected Page $page;

    public function __construct(Page $page)
    {
        $this->page = $page;
    }

    public function generateSiteSearchData(): array
    {
        return array_merge(
            $this->getCommonSiteSearchData(),
            $this->getTypeData(),
            $this->getContentData(),
            $this->getTypeSpecificData(),
        );
    }

    abstract protected function getContentData(): array;

    abstract protected function getTypeData(): array;

    protected function getTypeSpecificData(): array
    {
        return [];
    }

    private function getCommonSiteSearchData(): array
    {
        return [
            self::KEY_SITE_ID => $this->page->site_id,
            self::KEY_RELATIVE_URL => pageLink($this->page, false),
            self::KEY_NAME => $this->page->name,
        ];
    }
}
