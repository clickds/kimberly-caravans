<?php

namespace App\Http\Requests\Admin\Navigations\NavigationItems;

use App\Models\NavigationItem;
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
            'name' => [
                'required',
                'string',
            ],
            'page_id' => [
                'required_without:external_url',
                'nullable',
                Rule::exists('pages', 'id'),
            ],
            'query_parameters' => [
                'nullable',
                'string',
            ],
            'parent_id' => [
                Rule::exists('navigation_items', 'id')->where(function ($query) {
                    return $query->where('navigation_id', $this->navigationId());
                }),
                'nullable',
            ],
            'external_url' => [
                'required_without:page_id',
                'nullable',
                'url',
            ],
            'background_colour' => [
                'required',
                Rule::in(array_keys(NavigationItem::BACKGROUND_COLOURS)),
                'string',
            ],
        ];
    }

    private function navigationId(): ?int
    {
        $navigation = $this->route('navigation');
        if ($navigation) {
            return $navigation->id;
        }
        return null;
    }
}
