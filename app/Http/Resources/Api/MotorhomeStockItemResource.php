<?php

namespace App\Http\Resources\Api;

use App\Presenters\SitePresenter;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @extends JsonResource<\App\Models\MotorhomeStockItem>
 */
class MotorhomeStockItemResource extends JsonResource
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
            'berths' => $this->berths->map(function ($item) {
                return $item->number;
            }),
            'chassis_manufacturer' => $this->chassis_manufacturer,
            'condition' => $this->condition,
            'conversion' => $this->conversion,
            'demonstrator' => $this->demonstrator,
            'description' => $this->description,
            'designated_seats' => $this->seats->map(function ($item) {
                return $item->number;
            }),
            'engine_size' => $this->engine_size,
            'managers_special' => $this->managers_special,
            'fuel' => $this->fuel,
            'height' => $this->height,
            'layout_name' => $this->layout->name,
            'length' => $this->length,
            'mtplm' => $this->mtplm,
            'mileage' => $this->mileage,
            'payload' => $this->payload,
            'number_of_owners' => $this->number_of_owners,
            'currency_symbol' => $sitePresenter->currencySymbol(false),
            'price' => $this->price,
            'recommended_price' => $this->recommended_price,
            'registration_date' => $this->registration_date,
            'registration_number' => $this->registration_number,
            'source' => $this->source,
            'transmission' => $this->transmission,
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
            // loaded in the stock item query builder
            'special_offers' => StockItemSpecialOfferResource::collection($this->specialOffers),
            'features' => $this->features,
            'manufacturer' => $this->manufacturer->name,
            'model' => $modelName,
        ];
    }
}
