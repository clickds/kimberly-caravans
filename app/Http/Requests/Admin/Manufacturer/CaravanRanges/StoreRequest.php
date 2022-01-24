<?php

namespace App\Http\Requests\Admin\Manufacturer\CaravanRanges;

use App\Models\CaravanRange;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'exclusive' => [
                'required',
                'boolean',
            ],
            'image' => [
                'required',
                'image',
                Rule::dimensions()->minWidth(1920),
            ],
            'tab_content_image' => [
                'image',
                'nullable',
            ],
            'name' => [
                'required',
                'string',
                Rule::unique('caravan_ranges')->where(function ($query) {
                    $query->where('manufacturer_id', $this->manufacturerId());
                }),
            ],
            'prepend_range_name_to_model_names' => [
                'required',
                'boolean',
            ],
            'overview' => [
                'string',
            ],
            'position' => [
                'integer',
            ],
            'site_ids.*' => [
                'exists:sites,id',
            ],
            'primary_theme_colour' => [
                'string',
                'nullable',
                Rule::in($this->primaryThemeColours()),
            ],
            'secondary_theme_colour' => [
                'string',
                'nullable',
                Rule::in($this->secondaryThemeColours()),
            ],
            'live' => [
                'required',
                'boolean',
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

    private function primaryThemeColours(): array
    {
        return array_keys(CaravanRange::PRIMARY_THEME_COLOURS);
    }

    private function secondaryThemeColours(): array
    {
        return array_keys(CaravanRange::SECONDARY_THEME_COLOURS);
    }
}
