<?php

namespace App\Http\Requests\Admin\Manufacturers;

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
            'caravan_intro_text' => [
                'nullable',
                'string',
            ],
            'motorhome_intro_text' => [
                'nullable',
                'string',
            ],
            'caravan_image' => [
                'image',
                'nullable',
            ],
            'exclusive' => [
                'boolean',
                'required',
            ],
            'link_directly_to_stock_search' => [
                'boolean',
                'required',
            ],
            'motorhome_image' => [
                'image',
                'nullable',
            ],
            'name' => [
                'required',
                Rule::unique('manufacturers', 'name')->ignore($this->manufacturerId())
            ],
            'logo' => [
                'image',
                'nullable',
            ],
            'motorhome_position' => [
                'numeric',
                'nullable',
            ],
            'caravan_position' => [
                'numeric',
                'nullable',
            ],
            'sites' => [
                Rule::exists('sites', 'id')
            ],
        ];
    }

    private function manufacturerId(): ?int
    {
        if ($manufacturer = $this->route('manufacturer')) {
            return $manufacturer->id;
        }
        return null;
    }
}
