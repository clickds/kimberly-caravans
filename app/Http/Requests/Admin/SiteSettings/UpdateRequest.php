<?php

namespace App\Http\Requests\Admin\SiteSettings;

use App\Models\SiteSetting;
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
            'name' => [
                'required',
                Rule::in(SiteSetting::VALID_SETTINGS),
                Rule::unique('site_settings', 'name')->ignore($this->id()),
            ],
            'description' => [
                'nullable',
                'string',
            ],
            'value' => [
                'required',
            ],
        ];
    }

    private function id(): ?int
    {
        if ($site_setting = $this->route('site_setting')) {
            return $site_setting->id;
        }
        return null;
    }
}
