<?php

namespace App\Http\Requests\Admin\Motorhome\BedSizes;

use App\Models\Motorhome;
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
            'bed_description_id' => [
                'required',
                'exists:bed_descriptions,id',
                Rule::unique('bed_sizes')->where(function ($query) {
                    $query->where('vehicle_type', Motorhome::class)
                        ->where('vehicle_id', $this->motorhomeId());
                })->ignore($this->bedSizeId()),
            ],
            'details' => [
                'required',
                'string',
            ],
        ];
    }

    private function motorhomeId(): ?int
    {
        $motorhome = $this->route('motorhome');
        if ($motorhome) {
            return $motorhome->id;
        }
        return null;
    }

    private function bedSizeId(): ?int
    {
        $bedSize = $this->route('bed_size');
        if ($bedSize) {
            return $bedSize->id;
        }
        return null;
    }
}
