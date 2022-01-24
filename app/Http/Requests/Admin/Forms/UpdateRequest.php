<?php

namespace App\Http\Requests\Admin\Forms;

use App\Models\Form;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'crm_list' => [
                'nullable',
                'string',
            ],
            'name' => [
                'required',
                'string',
                Rule::unique('forms')->ignore($this->formId()),
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

    private function formId(): ?int
    {
        if ($form = $this->route('form')) {
            return $form->id;
        }
        return null;
    }
}
