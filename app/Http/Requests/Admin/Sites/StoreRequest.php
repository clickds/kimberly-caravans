<?php

namespace App\Http\Requests\Admin\Sites;

use DateTimeZone;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreRequest extends FormRequest
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
            'campaign_monitor_list_id' => [
                'nullable',
                'string',
            ],
            'country' => [
                'required',
                'unique:sites',
            ],
            'flag' => [
                'required',
            ],
            'footer_content' => [
                'nullable',
                'string',
            ],
            'is_default' => [
                'boolean',
                'required',
            ],
            'has_stock' => [
                'boolean',
                'required',
            ],
            'show_opening_times_and_telephone_number' => [
                'boolean',
                'required',
            ],
            'display_exclusive_manufacturers_separately' => [
                'boolean',
                'required',
            ],
            'show_buy_tab_on_new_model_pages' => [
                'boolean',
                'required',
            ],
            'show_offers_tab_on_new_model_pages' => [
                'boolean',
                'required',
            ],
            'show_dealer_ranges' => [
                'boolean',
                'required',
            ],
            'show_live_chat' => [
                'required',
                'boolean',
            ],
            'show_social_icons' => [
                'required',
                'boolean',
            ],
            'show_accreditation_icons' => [
                'required',
                'boolean',
            ],
            'show_footer_content' => [
                'required',
                'boolean',
            ],
            'phone_number' => [
                'max:255',
                'nullable',
                'string',
            ],
            'subdomain' => [
                'required',
                'unique:sites',
            ],
            'timezone' => [
                'required',
                'string',
                Rule::in(DateTimeZone::listIdentifiers()),
            ],
        ];
    }
}
