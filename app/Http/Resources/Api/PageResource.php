<?php

namespace App\Http\Resources\Api;

use App\Presenters\Page\SpecialOfferPresenter;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @extends JsonResource<\App\Models\Page>
 */
class PageResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $pagePresenter = (new SpecialOfferPresenter())->setWrappedObject($this);

        return [
            'id' => $this->id,
            'link' => $pagePresenter->link(),
        ];
    }
}
