<?php

namespace App\Http\Requests\Api\VehicleEnquiries;

use Illuminate\Foundation\Http\FormRequest;

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
            'first_name' => [
                'required',
                'max:255',
                'string',
            ],
            'surname' => [
                'required',
                'max:255',
                'string',
            ],
            'title' => [
                'required',
                'max:255',
                'string',
            ],
            'email' => [
                'required',
                'string',
                'email',
            ],
            'phone_number' => [
                'required',
                'string',
            ],
            'county' => [
                'required',
                'string',
            ],
            'message' => [
                'required',
                'string',
            ],
            'help_methods.*' => [
                'string',
            ],
            'interests.*' => [
                'string',
            ],
            'marketing_preferences.*' => [
                'string',
            ],
            'dealer_ids' => [
                'required',
                'array',
                'min:1',
            ],
            'dealer_ids.*' => [
                'exists:dealers,id',
            ],
        ];
    }

    public function messages()
    {
        return [
            'dealer_ids.required' => 'Please select at least one dealer.',
        ];
    }
}
