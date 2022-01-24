<?php

namespace App\Services\Navigation;

use App\Models\Navigation;
use App\Models\Site;
use Illuminate\Support\Collection;
use Throwable;

class FullMenuNavigationFetcher
{
    private Site $site;
    private Collection $displayablePageIds;

    public function __construct(Site $site, Collection $displayablePageIds)
    {
        $this->site = $site;
        $this->displayablePageIds = $displayablePageIds;
    }

    public function call(): Navigation
    {
        try {
            $navigation = $this->getSite()->navigations()->where('type', Navigation::TYPE_FULL_MENU)
                ->with([
                    'navigationItems' => function ($query) {
                        $query->with('page:id,slug,parent_id,template', 'page.parent:id,slug')->with([
                            'children' => function ($query) {
                                return $query->with('page:id,slug,parent_id', 'page.parent:id,slug,parent_id')
                                    ->displayable($this->getDisplayablePageIds())->orderBy('display_order', 'asc');
                            }
                        ])
                            ->whereNull('parent_id')
                            ->displayable($this->getDisplayablePageIds())
                            ->orderBy('display_order', 'asc');
                    }
                ])->firstOrFail();
        } catch (Throwable $e) {
            $navigation = new Navigation();
        }
        return $navigation;
    }

    private function getSite(): Site
    {
        return $this->site;
    }

    private function getDisplayablePageIds(): Collection
    {
        return $this->displayablePageIds;
    }
}
