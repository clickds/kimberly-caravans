<?php

namespace App\Http\Requests\Admin\Events;

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
            'name' => [
                'required',
                'string',
            ],
            'dealer_id' => [
                'nullable',
                'exists:dealers,id',
            ],
            'event_location_id' => [
                'required_without:dealer_id',
                'nullable',
                'exists:event_locations,id',
            ],
            'start_date' => [
                'required',
                'date'
            ],
            'end_date' => [
                'required',
                'date'
            ],
            'summary' => [
                'required',
                'string',
            ],
            'image' => [
                'required',
                'image'
            ],
        ];
    }
}
