<?php

namespace App\Http\Requests\Admin\CaravanRange\Caravans;

use App\Models\Caravan;
use App\Models\CaravanRange;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'axles' => [
                'required',
                Rule::in(Caravan::AXLES),
            ],
            'day_floorplan' => [
                'image',
                'nullable',
            ],
            'description' => [
                'required',
                'string',
            ],
            'exclusive' => [
                'boolean',
                'required',
            ],
            'height_includes_aerial' => [
                'required',
                'boolean',
            ],
            'height' => [
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
                Rule::unique('caravans')->where(function ($query) {
                    $query->where('year', $this->input('year'))
                        ->where('caravan_range_id', $this->caravanRangeId());
                })->ignore($this->caravanId()),
            ],
            'night_floorplan' => [
                'image',
                'nullable',
            ],
            'payload' => [
                'nullable',
                'integer',
            ],
            'position' => [
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
            'stock_item_image_ids' => [
                'array',
                'nullable',
            ],
            'stock_item_image_ids.*' => [
                Rule::exists('media', 'id')->where(function ($query) {
                    $query->where('model_type', CaravanRange::class)
                        ->where('model_id', $this->caravanRangeId());
                }),
            ],
            'video_id' => [
                'nullable',
                Rule::exists('caravan_range_videos', 'video_id')->where(function ($query) {
                    $query->where('caravan_range_id', $this->caravanRangeId());
                }),
            ],
        ];
    }

    private function caravanRangeId(): ?int
    {
        if ($caravanRange = $this->route('caravanRange')) {
            return $caravanRange->id;
        }
        return null;
    }

    private function caravanId(): ?int
    {
        if ($caravan = $this->route('caravan')) {
            return $caravan->id;
        }
        return null;
    }
}
