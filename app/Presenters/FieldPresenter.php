<?php

namespace App\Presenters;

use App\Models\BusinessArea;
use App\Models\Dealer;
use App\Models\Field;
use McCool\LaravelAutoPresenter\BasePresenter;

class FieldPresenter extends BasePresenter
{
    public function cssClasses(): string
    {
        $classes = ['field', 'mb-5', 'px-2'];
        $classes[] = $this->widthClasses();

        return implode(' ', $classes);
    }

    public function getOptions(SitePresenter $site): array
    {
        $field = $this->getWrappedObject();

        switch ($field->type) {
            case Field::TYPE_DEALER_SELECT:
            case Field::TYPE_DEALER_CHECKBOXES:
                return Dealer::branch()->where('site_id', $site->id)->pluck('name')->toArray();
            case Field::TYPE_BUSINESS_AREA_SELECT:
                return BusinessArea::select('name')->get()->pluck('name')->toArray();
            default:
                // Cast in case options is blank
                return (array) $field->options;
        }
    }

    private function widthClasses(): string
    {
        $field = $this->getWrappedObject();
        switch ($field->width) {
            case Field::WIDTH_HALF:
                return "w-full md:w-1/2";
            case Field::WIDTH_THIRD:
                return "w-full md:w-1/3";
            case Field::WIDTH_TWO_THIRDS:
                return "w-full md:w-2/3";
            default:
                return "w-full";
        }
    }
}
