<?php

namespace App\Http\Requests\Admin\Navigations;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\Navigation;

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
            'name' => [
                'required',
                Rule::unique('navigations', 'name')->where(function ($query) {
                    return $query->where('site_id', $this->get('site_id'));
                }),
            ],
            'site_id' => [
                Rule::exists('sites', 'id')
            ],
            'type' => [
                Rule::in(array_keys(Navigation::NAVIGATION_TYPES)),
                Rule::unique('navigations', 'type')->where(function ($query) {
                    return $query->where('site_id', $this->get('site_id'));
                }),
            ]
        ];
    }
}
