<?php

namespace App\Services\Search\Page\DataProviders;

use Illuminate\Support\Str;

final class PageDataProvider extends BaseDataProvider
{
    public const TYPE = 'Page';

    protected function getContentData(): array
    {
        return [self::KEY_CONTENT => $this->generateContentString()];
    }

    protected function getTypeData(): array
    {
        return [self::KEY_TYPE => self::TYPE];
    }

    private function generateContentString(): string
    {
        $firstEligableArea = $this->page->areas()
            ->published()
            ->live()
            ->notExpired()
            ->orderBy('position')
            ->first();

        if (is_null($firstEligableArea)) {
            return '';
        }

        $eligablePanel = $firstEligableArea->panels()
            ->published()
            ->live()
            ->notExpired()
            ->orderBy('position')
            ->whereNotNull('content')
            ->first();

        if (is_null($eligablePanel)) {
            return '';
        }

        return  Str::limit(strip_tags($eligablePanel->content), 7500);
    }
}
