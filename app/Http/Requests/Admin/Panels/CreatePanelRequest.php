<?php

namespace App\Http\Requests\Admin\Panels;

use App\Models\Panel;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreatePanelRequest extends FormRequest
{
    public function rules(): array
    {
        $defaultRules = $this->defaultRules();
        $panelTypeRules = $this->panelTypeRules();

        return array_merge($defaultRules, $panelTypeRules);
    }

    private function panelTypeRules(): array
    {
        switch ($this->get('type')) {
            case Panel::TYPE_FEATURED_IMAGE:
                return $this->featuredImagePanelRules();
            case Panel::TYPE_IMAGE:
                return $this->imagePanelRules();
            case Panel::TYPE_QUOTE:
                return $this->quotePanelRules();
            case Panel::TYPE_MANUFACTURER_SLIDER:
                return $this->manufacturerSliderPanelRules();
            case Panel::TYPE_READ_MORE:
                return $this->readMorePanelRules();
            case Panel::TYPE_SPECIAL_OFFERS:
                return $this->specialOfferPanelRules();
            case Panel::TYPE_STANDARD:
                return $this->standardPanelRules();
            case Panel::TYPE_SEARCH_BY_BERTH:
                return $this->searchByBerthPanelRules();
            case Panel::TYPE_STOCK_ITEM_CATEGORY_TABS:
                return $this->stockItemCategoryTabsPanelRules();
            case Panel::TYPE_FORM:
                return $this->formPanelRules();
            case Panel::TYPE_VIDEO:
                return $this->videoPanelRules();
            case Panel::TYPE_HTML:
                return $this->htmlPanelRules();
            case Panel::TYPE_BROCHURE:
                return $this->brochurePanelRules();
            case Panel::TYPE_REVIEW:
                return $this->reviewPanelRules();
            case Panel::TYPE_EVENT:
                return $this->eventPanelRules();
            default:
                return [];
        }
    }

    private function formPanelRules(): array
    {
        return [
            'content' => [
                'nullable',
                'string',
            ],
            'featureable_id' => [
                'required',
                'exists:forms,id',
            ],
            'featureable_type' => [
                'required',
                'string',
            ],
        ];
    }

    private function videoPanelRules(): array
    {
        return [
            'content' => [
                'nullable',
                'string',
            ],
            'featureable_id' => [
                'required',
                'exists:videos,id',
            ],
            'featureable_type' => [
                'required',
                'string',
            ],
        ];
    }

    private function manufacturerSliderPanelRules(): array
    {
        return [
            'vehicle_type' => [
                'required',
                Rule::in(array_keys(Panel::VEHICLE_TYPES)),
            ],
        ];
    }

    private function featuredImagePanelRules(): array
    {
        return [
            'featured_image_content' => [
                'required',
                'string',
                'max:250',
            ],
            'featured_image_alt_text' => [
                'required',
                'string',
            ],
            'featured_image' => [
                'required',
                'image',
                'max:5000',
                Rule::dimensions()->minWidth(1980),
            ],
            'heading' => [
                'nullable',
                'string',
            ],
            'overlay_position' => [
                'required',
                Rule::in(array_keys(Panel::OVERLAY_POSITIONS)),
            ],
        ];
    }

    private function imagePanelRules(): array
    {
        return [
            'image' => [
                'required',
                'image',
                'max:5000',
            ],
            'image_alt_text' => [
                'required',
                'string',
            ],
            'external_url' => [
                'nullable',
                'url',
            ],
            'page_id' => [
                'nullable',
                'exists:pages,id',
            ],
        ];
    }

    private function quotePanelRules(): array
    {
        return [
            'content' => [
                'required',
                'string',
            ],
        ];
    }

    private function readMorePanelRules(): array
    {
        return [
            'content' => [
                'required',
                'string',
            ],
            'read_more_content' => [
                'required',
                'string',
            ],
        ];
    }

    private function specialOfferPanelRules(): array
    {
        return [
            'special_offer_ids' => [
                'array',
                'required',
            ],
            'special_offer_ids.*' => [
                Rule::exists('special_offers', 'id'),
            ],
        ];
    }

    private function standardPanelRules(): array
    {
        return [
            'content' => [
                'required',
                'string',
            ],
        ];
    }

    private function searchByBerthPanelRules(): array
    {
        return [
            'vehicle_type' => [
                'required',
                Rule::in(array_keys(Panel::VEHICLE_TYPES)),
            ],
        ];
    }

    private function stockItemCategoryTabsPanelRules(): array
    {
        return [
            'vehicle_type' => [
                'required',
                Rule::in(array_keys(Panel::VEHICLE_TYPES)),
            ],
        ];
    }

    private function htmlPanelRules(): array
    {
        return [
            'html_content' => [
                'required',
                'string',
            ],
        ];
    }

    private function brochurePanelRules(): array
    {
        return [
            'featureable_id' => [
                'required',
                'exists:brochures,id',
            ],
            'featureable_type' => [
                'required',
                'string',
            ],
        ];
    }

    private function reviewPanelRules(): array
    {
        return [
            'featureable_id' => [
                'required',
                'exists:reviews,id',
            ],
            'featureable_type' => [
                'required',
                'string',
            ],
        ];
    }

    private function eventPanelRules(): array
    {
        return [
            'featureable_id' => [
                'required',
                'exists:events,id',
            ],
            'featureable_type' => [
                'required',
                'string',
            ],
        ];
    }

    private function defaultRules(): array
    {
        return [
            'area_id' => [
                'required',
                'exists:areas,id',
            ],
            'expired_at' => [
                'date',
                'nullable',
            ],
            'heading' => [
                'string',
                'nullable',
            ],
            'heading_type' => [
                'required',
                'string',
                Rule::in(Panel::HEADING_TYPES),
            ],
            'live' => [
                'required',
                'boolean',
            ],
            'name' => [
                'required',
                'string',
            ],
            'position' => [
                'integer',
                'nullable',
            ],
            'published_at' => [
                'date',
                'nullable',
            ],
            'text_alignment' => [
                'required',
                Rule::in(array_keys(Panel::TEXT_ALIGNMENTS)),
            ],
            'type' => [
                'required',
                Rule::in(array_keys(Panel::TYPES)),
            ],
            'vertical_positioning' => [
                'required',
                Rule::in(array_keys(Panel::VERTICAL_POSITIONS)),
            ],
        ];
    }
}
