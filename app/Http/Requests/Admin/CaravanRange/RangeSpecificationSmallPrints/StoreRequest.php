<?php

namespace App\Http\Requests\Admin\CaravanRange\RangeSpecificationSmallPrints;

use App\Models\CaravanRange;
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
                    $query->where('vehicle_range_id', $this->caravanRangeId())
                        ->where('vehicle_range_type', CaravanRange::class)
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

    private function caravanRangeId(): ?int
    {
        $range = $this->route('caravanRange');
        if ($range) {
            return $range->id;
        }
        return null;
    }
}
