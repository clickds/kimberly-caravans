<?php

namespace App\Http\Requests\Admin\Ctas;

use App\Models\Cta;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreRequest extends FormRequest
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
            'excerpt_text' => [
                Rule::requiredIf(function () {
                    return $this->get('type') == Cta::TYPE_STANDARD;
                }),
                'string',
                'nullable',
            ],
            'title' => [
                'required',
                'string',
                'max:255',
            ],
            'image' => [
                'required',
                'image',
            ],
            'link_text' => [
                Rule::requiredIf(function () {
                    return $this->get('type') != Cta::TYPE_NEWS_AND_INFO_LANDER;
                }),
                'string',
                'max:255',
                'nullable',
            ],
            'page_id' => [
                'nullable',
                Rule::exists('pages', 'id')->where(function ($query) {
                    $query->where('site_id', $this->get('site_id'));
                }),
            ],
            'position' => [
                'integer',
                'nullable',
            ],
            'site_id' => [
                'required',
                Rule::exists('sites', 'id'),
            ],
            'type' => [
                'required',
                Rule::in(Cta::TYPES),
            ],
        ];
    }
}
