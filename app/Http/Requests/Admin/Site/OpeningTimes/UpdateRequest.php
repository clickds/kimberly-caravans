<?php

namespace App\Http\Requests\Admin\Site\OpeningTimes;

use Carbon\Carbon;
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
            'closes_at' => [
                'after:opens_at',
                'required',
            ],
            'day' => [
                'required',
                Rule::in(array_keys(Carbon::getDays())),
                Rule::unique('opening_times', 'day')->where(function ($query) {
                    $query->where('site_id', $this->siteId());
                })->ignore($this->id()),
            ],
            'opens_at' => [
                'before:closes_at',
                'required',
            ],
            'closed' => [
                'required',
                'boolean',
            ],
        ];
    }

    private function siteId(): ?int
    {
        $site = $this->route('site');
        if (is_null($site)) {
            return null;
        }
        return $site->id;
    }

    private function id(): ?int
    {
        $openingTime = $this->route('opening_time');
        if (is_null($openingTime)) {
            return null;
        }
        return $openingTime->id;
    }
}
