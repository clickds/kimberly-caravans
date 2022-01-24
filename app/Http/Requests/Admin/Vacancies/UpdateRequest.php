<?php

namespace App\Http\Requests\Admin\Vacancies;

use Illuminate\Foundation\Http\FormRequest;

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
            'title' => [
                'required',
            ],
            'salary' => [
                'nullable',
                'string',
            ],
            'short_description' => [
                'required',
                'string',
            ],
            'description' => [
                'required',
                'string',
            ],
            'dealer_ids' => [
                'required',
                'array',
            ],
            'dealer_ids.*' => [
                'exists:dealers,id',
            ],
            'published_at' => [
                'required',
                'date',
            ],
            'expired_at' => [
                'date',
                'nullable',
            ],
            'notification_email_address' => [
                'email',
                'nullable',
            ],
        ];
    }
}
