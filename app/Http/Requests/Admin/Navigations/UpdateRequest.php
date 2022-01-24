<?php

namespace App\Http\Requests\Admin\Navigations;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\Navigation;

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

    public function rules(): array
    {
        return [
            'name' => [
                'required',
                Rule::unique('navigations', 'name')->where(function ($query) {
                    return $query->where('site_id', $this->get('site_id'));
                })->ignore($this->navigationId()),
            ],
            'site_id' => [
                Rule::exists('sites', 'id')
            ],
            'navigation-hierarchy' => [
                'string'
            ],
            'type' => [
                Rule::in(array_keys(Navigation::NAVIGATION_TYPES)),
                Rule::unique('navigations', 'type')->where(function ($query) {
                    return $query->where('site_id', $this->get('site_id'));
                })->ignore($this->navigationId()),
            ]
        ];
    }

    private function navigationId(): ?int
    {
        if ($navigation = $this->route('navigation')) {
            return $navigation->id;
        }
        return null;
    }
}
