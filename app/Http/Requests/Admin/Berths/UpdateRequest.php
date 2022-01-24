<?php

namespace App\Http\Requests\Admin\Berths;

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
                Rule::unique('berths')->ignore($this->id()),
            ],
        ];
    }

    private function id(): ?int
    {
        if ($berth = $this->route('berth')) {
            return $berth->id;
        }
        return null;
    }
}
