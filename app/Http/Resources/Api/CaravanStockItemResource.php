<?php

namespace App\Http\Resources\Api;

use App\Presenters\SitePresenter;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @extends JsonResource<\App\Models\CaravanStockItem>
 */
class CaravanStockItemResource extends JsonResource
{
    public function toArray($request)
    {
        $site = resolve('currentSite');
        $sitePresenter = (new SitePresenter())->setWrappedObject($site);
        $modelName = $this->shouldPrependRangeName()
            ? sprintf('%s %s', $this->rangeName(), $this->model)
            : $this->model;

        return [
            'id' => $this->id,
            'stock_type' => $this->stockType(),
            'attention_grabber' => $this->attention_grabber,
            'axles' => $this->axles,
            'berths' => $this->berths->map(function ($item) {
                return $item->number;
            }),
            'condition' => $this->condition,
            'demonstrator' => $this->demonstrator,
            'description' => $this->description,
            'managers_special' => $this->managers_special,
            'height' => $this->height,
            'layout_name' => $this->layout->name,
            'length' => $this->length,
            'mtplm' => $this->mtplm,
            'number_of_owners' => $this->number_of_owners,
            'payload' => $this->payload,
            'currency_symbol' => $sitePresenter->currencySymbol(false),
            'price' => $this->price,
            'recommended_price' => $this->recommended_price,
            'registration_date' => $this->registration_date,
            'source' => $this->source,
            'unique_code' => $this->unique_code,
            'mro' => $this->mro,
            'width' => $this->width,
            'year' => $this->year,
            'images' => $this->modelImages()->map(function ($media) {
                return $media->getUrl('stockListing');
            }),
            'floorplan_images' => $this->floorplanImages()->map(function ($media) {
                return $media->getUrl();
            }),
            'detail_page_url' => pageLink($this->sitePage($site)),
            'delivery_date' => $this->delivery_date,
            'special_offers' => StockItemSpecialOfferResource::collection($this->specialOffers),
            'features' => $this->features,
            'manufacturer' => $this->manufacturer->name,
            'model' => $modelName,
        ];
    }
}
