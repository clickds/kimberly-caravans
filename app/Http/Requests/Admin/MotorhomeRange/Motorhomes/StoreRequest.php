<?php

namespace App\Http\Requests\Admin\MotorhomeRange\Motorhomes;

use App\Models\Motorhome;
use App\Models\MotorhomeRange;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

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
            'chassis_manufacturer' => [
                'required',
                'string',
            ],
            'conversion' => [
                'required',
                Rule::in(Motorhome::CONVERSIONS),
            ],
            'description' => [
                'required',
                'string',
            ],
            'engine_size' => [
                'required',
                'string',
                'max:255',
            ],
            'engine_power' => [
                'required',
                'string',
                'max:255',
            ],
            'day_floorplan' => [
                'required',
                'image',
            ],
            'exclusive' => [
                'boolean',
                'required',
            ],
            'fuel' => [
                'required',
                Rule::in(Motorhome::FUELS),
            ],
            'height_includes_aerial' => [
                'boolean',
                'required',
            ],
            'height' => [
                'numeric',
                'nullable',
            ],
            'high_line_height' => [
                'numeric',
                'nullable',
            ],
            'layout_id' => [
                'exists:layouts,id',
                'required',
            ],
            'live' => [
                'required',
                'boolean',
            ],
            'length' => [
                'numeric',
                'nullable',
            ],
            'mtplm' => [
                'nullable',
                'integer',
            ],
            'name' => [
                'required',
                'string',
                Rule::unique('motorhomes')->where(function ($query) {
                    $query->where('year', $this->input('year'))
                        ->where('motorhome_range_id', $this->motorhomeRangeId());
                }),
            ],
            'night_floorplan' => [
                'image',
                'nullable',
            ],
            'payload' => [
                'nullable',
                'integer',
            ],
            'sites.*.id' => [
                'exists:sites,id',
            ],
            'sites.*.price' => [
                'gte:0',
                'numeric',
                'nullable',
            ],
            'sites.*.recommended_price' => [
                'gte:0',
                'numeric',
                'nullable',
            ],
            'small_print' => [
                'nullable',
                'string',
            ],
            'position' => [
                'numeric',
                'nullable',
            ],
            'transmission' => [
                'required',
                Rule::in(Motorhome::TRANSMISSIONS),
            ],
            'mro' => [
                'nullable',
                'integer',
            ],
            'width' => [
                'numeric',
                'nullable',
            ],
            'year' => [
                'integer',
                'required',
            ],
            'berth_ids.*' => [
                'exists:berths,id',
            ],
            'seat_ids.*' => [
                'exists:seats,id',
            ],
            'stock_item_image_ids' => [
                'array',
                'nullable',
            ],
            'stock_item_image_ids.*' => [
                Rule::exists('media', 'id')->where(function ($query) {
                    $query->where('model_type', MotorhomeRange::class)
                        ->where('model_id', $this->motorhomeRangeId());
                }),
            ],
            'video_id' => [
                'nullable',
                Rule::exists('motorhome_range_videos', 'video_id')->where(function ($query) {
                    $query->where('motorhome_range_id', $this->motorhomeRangeId());
                }),
            ],
            'maximum_trailer_weight' => [
                'numeric',
                'nullable',
            ],
            'gross_train_weight' => [
                'numeric',
                'nullable',
            ],
        ];
    }

    private function motorhomeRangeId(): ?int
    {
        if ($motorhomeRange = $this->route('motorhomeRange')) {
            return $motorhomeRange->id;
        }
        return null;
    }
}
