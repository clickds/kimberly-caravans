<?php

namespace App\Http\Requests\Admin\Aliases;

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
            'capture_path' => [
                'required',
                Rule::unique('aliases')->ignore($this->aliasId()),
                'starts_with:/',
            ],
            'site_id' => [
                'required',
                'exists:sites,id',
            ],
            'page_id' => [
                'required',
                'exists:pages,id',
            ],
        ];
    }

    private function aliasId(): ?int
    {
        if ($alias = $this->route('alias')) {
            return $alias->id;
        }
        return null;
    }
}
