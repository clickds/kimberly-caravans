<?php

namespace App\Http\Requests\Admin\MotorhomeRange\RangeSpecificationSmallPrints;

use App\Models\MotorhomeRange;
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
            'content' => [
                'required',
                'string',
            ],
            'name' => [
                'required',
                'string',
                Rule::unique('range_specification_small_prints')->where(function ($query) {
                    $query->where('vehicle_range_id', $this->motorhomeRangeId())
                        ->where('vehicle_range_type', MotorhomeRange::class)
                        ->where('site_id', $this->get('site_id'));
                }),
            ],
            'position' => [
                'integer',
                'nullable',
            ],
            'site_id' => [
                'required',
                'exists:sites,id',
            ],
        ];
    }

    private function motorhomeRangeId(): ?int
    {
        $range = $this->route('motorhomeRange');
        if ($range) {
            return $range->id;
        }
        return null;
    }
}
