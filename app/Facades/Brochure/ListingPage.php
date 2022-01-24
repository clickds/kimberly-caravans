<?php

namespace App\Facades\Brochure;

use App\Facades\BasePage;
use App\Models\Brochure;
use App\Models\BrochureGroup;
use App\Models\Page;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Collection as SupportCollection;
use Illuminate\Http\Request;

class ListingPage extends BasePage
{
    private Collection $brochureGroups;
    private ?Page $brochureByPostPage;

    public function __construct(Page $page, Request $request)
    {
        parent::__construct($page, $request);

        $this->brochureGroups = $this->fetchBrochureGroups();
    }

    public function getBrochureByPostPage(): ?Page
    {
        if (!isset($this->brochureByPostPage)) {
            $this->brochureByPostPage = Page::where('site_id', $this->getSite()->id)
                ->template(Page::TEMPLATE_BROCHURES_BY_POST)->with('parent:id,slug')
                ->select('id', 'slug')->first();
        }
        return $this->brochureByPostPage;
    }


    public function getBrochureGroups(): Collection
    {
        return $this->brochureGroups;
    }

    private function fetchBrochureGroups(): Collection
    {
        $ids = $this->brochureGroupIdsWithDisplayableBrochures();
        return BrochureGroup::whereIn('id', $ids)
            ->with([
                'brochures' => function ($query) {
                    return $query->forSite($this->getPage()->site_id)
                        ->displayable()->orderBy('title', 'asc');
                }
            ])->orderBy('position', 'asc')->get();
    }

    private function brochureGroupIdsWithDisplayableBrochures(): SupportCollection
    {
        return Brochure::displayable()->toBase()->pluck('group_id');
    }
}
