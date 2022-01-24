<?php

namespace App\Http\Requests\Admin\EmailRecipients;

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
            'name' => [
                'required',
                'string',
                'max:255',
            ],
            'email' => [
                'required',
                Rule::unique('email_recipients')->ignore($this->id()),
                'email',
                'max:255',
            ],
            'receives_vehicle_enquiry' => [
                'required',
                'boolean',
            ],
        ];
    }

    private function id(): ?int
    {
        if ($recipient = $this->route('email_recipient')) {
            return $recipient->id;
        }
        return null;
    }
}
