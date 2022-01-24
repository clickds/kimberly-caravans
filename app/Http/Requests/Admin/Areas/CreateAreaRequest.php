<?php

namespace App\Http\Requests\Admin\Areas;

use App\Models\Area;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateAreaRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'background_colour' => [
                'required',
                Rule::in(array_keys(Area::BACKGROUND_COLOURS)),
                'string',
            ],
            'columns' => [
                'required',
                'integer',
                Rule::in(Area::COLUMNS),
            ],
            'expired_at' => [
                'nullable',
                'date',
            ],
            'heading' => [
                'max:100',
                'string',
                'nullable',
            ],
            'heading_text_alignment' => [
                'required',
                Rule::in(array_keys(Area::TEXT_ALIGNMENTS)),
                'string',
            ],
            'heading_type' => [
                'required',
                Rule::in(Area::HEADING_TYPES),
            ],
            'holder' => [
                'required',
                'max:100',
                'string',
                Rule::in($this->availableHolders()),
            ],
            'live' => [
                'required',
                'boolean',
            ],
            'name' => [
                'required',
                'string',
            ],
            'position' => [
                'integer',
                'nullable',
            ],
            'published_at' => [
                'nullable',
                'date',
            ],
            'width' => [
                'required',
                Rule::in(array_keys(Area::WIDTHS)),
                'string',
            ],
        ];
    }

    private function availableHolders(): array
    {
        $page = $this->route('page');
        if (is_null($page)) {
            return [];
        }
        return $page->availableHolders();
    }
}
