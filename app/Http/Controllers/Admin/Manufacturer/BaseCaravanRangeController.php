<?php

namespace App\Http\Controllers\Admin\Manufacturer;

use App\Http\Controllers\Admin\BaseController;
use App\Models\CaravanRange;
use App\Models\Site;
use App\Services\Pageable\CaravanRangePageSaver;

abstract class BaseCaravanRangeController extends BaseController
{
    protected function updateSitePages(CaravanRange $range, array $siteIds): void
    {
        $range->pages()->whereNotIn('site_id', $siteIds)->delete();

        $sites = Site::whereIn('id', $siteIds)->get();

        foreach ($sites as $site) {
            $saver = new CaravanRangePageSaver($range, $site);
            $saver->call();
        }
    }

    protected function primaryThemeColours(): array
    {
        return CaravanRange::PRIMARY_THEME_COLOURS;
    }

    protected function secondaryThemeColours(): array
    {
        return CaravanRange::SECONDARY_THEME_COLOURS;
    }
}
