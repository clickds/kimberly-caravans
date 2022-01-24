<?php

namespace App\Http\Requests\Admin\Caravan\BedSizes;

use App\Models\Caravan;
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
            'bed_description_id' => [
                'required',
                'exists:bed_descriptions,id',
                Rule::unique('bed_sizes')->where(function ($query) {
                    $query->where('vehicle_type', Caravan::class)
                        ->where('vehicle_id', $this->caravanId());
                }),
            ],
            'details' => [
                'required',
                'string',
            ],
        ];
    }

    private function caravanId(): ?int
    {
        $caravan = $this->route('caravan');
        if ($caravan) {
            return $caravan->id;
        }
        return null;
    }
}
