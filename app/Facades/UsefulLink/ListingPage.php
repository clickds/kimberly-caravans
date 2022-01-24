<?php

namespace App\Facades\UsefulLink;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use App\Facades\BasePage;
use App\Models\UsefulLinkCategory;
use App\Models\UsefulLink;
use App\Models\Page;

class ListingPage extends BasePage
{
    /**
     * @var Collection
     */
    private $usefulLinkCategories;

    public function __construct(Page $page, Request $request)
    {
        parent::__construct($page, $request);

        $this->usefulLinkCategories = $this->fetchUsefulLinkCategories();
    }

    public function getUsefulLinkCategories(): Collection
    {
        return $this->usefulLinkCategories;
    }

    private function fetchUsefulLinkCategories(): Collection
    {
        // Only get categories with links
        $usefulLinkCategoryIds = UsefulLink::toBase()->select('useful_link_category_id')
            ->distinct()->pluck('useful_link_category_id');

        return UsefulLinkCategory::with([
            'usefulLinks' => function ($query) {
                $query->orderBy('position', 'asc');
            },
        ])->whereIn('id', $usefulLinkCategoryIds)
            ->orderBy('position', 'asc')->get();
    }
}
