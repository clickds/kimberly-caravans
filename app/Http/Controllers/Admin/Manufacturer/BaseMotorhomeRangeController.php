<?php

namespace App\Http\Controllers\Admin\Manufacturer;

use App\Http\Controllers\Admin\BaseController;
use App\Models\MotorhomeRange;
use App\Models\Site;
use App\Services\Pageable\MotorhomeRangePageSaver;

abstract class BaseMotorhomeRangeController extends BaseController
{
    protected function primaryThemeColours(): array
    {
        return MotorhomeRange::PRIMARY_THEME_COLOURS;
    }

    protected function secondaryThemeColours(): array
    {
        return MotorhomeRange::SECONDARY_THEME_COLOURS;
    }

    protected function updateSitePages(MotorhomeRange $range, array $siteIds): void
    {
        $range->pages()->whereNotIn('site_id', $siteIds)->delete();

        $sites = Site::whereIn('id', $siteIds)->get();

        foreach ($sites as $site) {
            $saver = new MotorhomeRangePageSaver($range, $site);
            $saver->call();
        }
    }
}
