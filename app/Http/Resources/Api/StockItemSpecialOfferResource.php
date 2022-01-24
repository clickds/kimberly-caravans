<?php

namespace App\Http\Resources\Api;

use App\Services\SpecialOffer\IconFileReader;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @extends JsonResource<\App\Models\SpecialOffer>
 */
class StockItemSpecialOfferResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $iconReader = new IconFileReader($this->icon);
        $svgIcon = $iconReader->call();

        return [
            'id' => $this->id,
            'name' => $this->name,
            'icon' => $this->icon,
            'icon_svg' => $svgIcon,
            'stock_bar_colour' => $this->stock_bar_colour,
            'stock_bar_text_colour' => $this->stock_bar_text_colour,
            'pages' => PageResource::collection($this->pages),
        ];
    }
}
