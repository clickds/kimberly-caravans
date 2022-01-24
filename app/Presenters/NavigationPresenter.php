<?php

namespace App\Presenters;

use App\Models\Page;
use Illuminate\Database\Eloquent\Collection;
use McCool\LaravelAutoPresenter\BasePresenter;

class NavigationPresenter extends BasePresenter
{
    public function newCaravansPage(): ?NavigationItemPresenter
    {
        $navigation = $this->getWrappedObject();
        return $navigation->navigationItems->first(function ($navigationItem) {
            $page = $navigationItem->page;
            if (is_null($page)) {
                return false;
            }
            return $page->hasTemplate(Page::TEMPLATE_NEW_CARAVANS);
        });
    }

    public function newMotorhomesPage(): ?NavigationItemPresenter
    {
        $navigation = $this->getWrappedObject();
        return $navigation->navigationItems->first(function ($navigationItem) {
            $page = $navigationItem->page;
            if (is_null($page)) {
                return false;
            }
            return $page->hasTemplate(Page::TEMPLATE_NEW_MOTORHOMES);
        });
    }
}
