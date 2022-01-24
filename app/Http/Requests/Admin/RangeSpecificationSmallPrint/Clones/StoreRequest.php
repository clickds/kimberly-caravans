<?php

namespace App\Http\Requests\Admin\RangeSpecificationSmallPrint\Clones;

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
            'site_id' => [
                'required',
                'exists:sites,id',
                Rule::unique('range_specification_small_prints')->where(function ($query) {
                    $query->where('vehicle_range_type', $this->vehicleRangeType())
                        ->where('vehicle_range_id', $this->vehicleRangeId())
                        ->where('site_id', $this->get('site_id'));
                }),
            ],
        ];
    }

    private function vehicleRangeType(): ?string
    {
        $smallPrint = $this->route('rangeSpecificationSmallPrint');
        if (is_null($smallPrint)) {
            return null;
        }
        return $smallPrint->vehicle_range_type;
    }

    private function vehicleRangeId(): ?int
    {
        $smallPrint = $this->route('rangeSpecificationSmallPrint');
        if (is_null($smallPrint)) {
            return null;
        }
        return $smallPrint->vehicle_range_id;
    }
}
