<?php

namespace App\Http\Requests\Admin\Forms;

use App\Models\Form;
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
            'crm_list' => [
                'nullable',
                'string',
            ],
            'name' => [
                'required',
                'string',
                'unique:forms',
            ],
            'type' => [
                'required',
                Rule::in(Form::VALID_TYPES),
            ],
            'email_to' => [
                'email',
                'required',
                'string',
            ],
            'successful_submission_message' => [
                'required',
                'string',
            ],
            'carbon_copy_ids.*' => [
                'exists:email_recipients,id',
            ],
        ];
    }
}
