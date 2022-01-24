<?php

namespace App\Http\Requests\Admin\SpecialOffers;

use App\Models\SpecialOffer;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'content' => [
                'required',
                'string',
            ],
            'expired_at' => [
                'date',
                'nullable',
            ],
            'live' => [
                'required',
                'boolean',
            ],
            'offer_type' => [
                'required',
                'string',
                Rule::in(SpecialOffer::OFFER_TYPES),
            ],
            'icon' => [
                'required',
                'string',
            ],
            'name' => [
                'required',
                'string',
            ],
            'position' => [
                'required',
                'integer',
                'min:0',
            ],
            'square_image' => [
                'image',
                Rule::dimensions()->minWidth(480),
                'nullable',
            ],
            'site_ids' => [
                'required',
                'array',
            ],
            'site_ids.*' => [
                'required',
                'exists:sites,id',
            ],
            'landscape_image' => [
                'image',
                Rule::dimensions()->minWidth(960),
                'nullable',
            ],
            'type' => [
                'required',
                'string',
                Rule::in(SpecialOffer::TYPES),
            ],
            'published_at' => [
                'date',
                'nullable',
            ],
            'link_used_caravan_stock' => [
                'required',
                'boolean',
            ],
            'link_used_motorhome_stock' => [
                'required',
                'boolean',
            ],
            'link_managers_special_stock' => [
                'required',
                'boolean',
            ],
            'link_feed_special_offers_caravans' => [
                'required',
                'boolean',
            ],
            'link_feed_special_offers_motorhomes' => [
                'required',
                'boolean',
            ],
            'link_on_sale_stock' => [
                'required',
                'boolean',
            ],
            'stock_bar_colour' => [
                'required',
                Rule::in(array_keys(SpecialOffer::STOCK_BAR_COLOURS)),
            ],
            'stock_bar_text_colour' => [
                'required',
                Rule::in(array_keys(SpecialOffer::STOCK_BAR_TEXT_COLOURS)),
            ],
            'caravan_ids.*' => [
                'exists:caravans,id',
            ],
            'motorhome_ids.*' => [
                'exists:motorhomes,id',
            ],
            'feed_caravan_stock_item_ids.*' => [
                'exists:caravan_stock_items,id',
            ],
            'feed_motorhome_stock_item_ids.*' => [
                'exists:motorhome_stock_items,id',
            ],
            'url' => [
                'url',
                'nullable',
            ],
            'document' => [
                'file',
                'nullable',
            ],
        ];
    }
}
