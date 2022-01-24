<?php

namespace App\Presenters\Page;

use App\Models\Page;

class SpecialOfferPresenter extends BasePagePresenter
{
    public function linkText(): string
    {
        if ($this->template == Page::TEMPLATE_SPECIAL_OFFER_CARAVAN_SHOW) {
            return 'View Caravans';
        }
        return 'View Motorhomes';
    }
}
