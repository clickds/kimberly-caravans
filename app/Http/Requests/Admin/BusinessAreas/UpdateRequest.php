<?php

namespace App\Http\Requests\Admin\BusinessAreas;

use App\Models\BusinessArea;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Route;
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
                Rule::unique('business_areas', 'name')->ignore($this->getBusinessArea()->id),
            ],
            'email' => [
                'required',
                'email',
            ],
        ];
    }

    private function getBusinessArea(): ?BusinessArea
    {
        return Route::getCurrentRoute()->parameter('business_area');
    }
}
