<?php

namespace App\Facades\Brochure;

use App\Facades\BasePage;
use App\Models\Page;

class ByPostPage extends BasePage
{
    private ?Page $brochureListingPage;

    public function getBrochureListingPage(): ?Page
    {
        if (!isset($this->brochureListingPage)) {
            $this->brochureListingPage = Page::where('site_id', $this->getSite()->id)
                ->template(Page::TEMPLATE_BROCHURES_LISTING)->with('parent:id,slug')
                ->select('id', 'slug')->first();
        }
        return $this->brochureListingPage;
    }
}
