<?php

namespace App\Http\Requests\Admin\Users;

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
                Rule::unique('users')->ignore($this->editingUserId()),
                'string',
                'max:255',
            ],
            'password' => [
                'nullable',
                'string',
            ],
            'super' => [
                'required',
                'boolean',
            ],
        ];
    }

    private function editingUserId(): ?int
    {
        if ($user = $this->route('user')) {
            return $user->id;
        }
        return null;
    }
}
