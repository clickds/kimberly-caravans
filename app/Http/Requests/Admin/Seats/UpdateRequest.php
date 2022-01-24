<?php

namespace App\Http\Requests\Admin\Seats;

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
            'number' => [
                'required',
                'integer',
                'min:1',
                Rule::unique('seats')->ignore($this->id()),
            ],
        ];
    }

    private function id(): ?int
    {
        if ($seat = $this->route('seat')) {
            return $seat->id;
        }
        return null;
    }
}
