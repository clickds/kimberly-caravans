<?php

namespace App\Services\Search\MotorhomeStockItem;

use App\Models\MotorhomeStockItem;
use App\Presenters\StockItem\MotorhomePresenter;

final class DataProvider
{
    public static function getSearchIndexData(MotorhomeStockItem $motorhomeStockItem): array
    {
        $motorhomeStockItemPresenter = (new MotorhomePresenter())->setWrappedObject($motorhomeStockItem);

        return [
            'id' => $motorhomeStockItem->id,
            'stock_type' => $motorhomeStockItem->stockType(),
            'attention_grabber' => $motorhomeStockItem->attention_grabber,
            'berths' => $motorhomeStockItem->berths->map(function ($item) {
                return $item->number;
            }),
            'chassis_manufacturer' => $motorhomeStockItem->chassis_manufacturer,
            'condition' => $motorhomeStockItem->condition,
            'conversion' => $motorhomeStockItem->conversion,
            'demonstrator' => $motorhomeStockItem->demonstrator,
            'description' => $motorhomeStockItem->description,
            'designated_seats' => $motorhomeStockItem->seats->map(function ($item) {
                return $item->number;
            }),
            'engine_size' => $motorhomeStockItem->engine_size,
            'managers_special' => $motorhomeStockItem->managers_special,
            'fuel' => $motorhomeStockItem->fuel,
            'height' => $motorhomeStockItem->height,
            'layout_name' => is_null($motorhomeStockItem->layout) ? '' : $motorhomeStockItem->layout->name,
            'length' => $motorhomeStockItem->length,
            'mtplm' => $motorhomeStockItem->mtplm,
            'mileage' => $motorhomeStockItem->mileage,
            'payload' => $motorhomeStockItem->payload,
            'number_of_owners' => $motorhomeStockItem->number_of_owners,
            'price' => $motorhomeStockItem->price,
            'recommended_price' => $motorhomeStockItem->recommended_price,
            'registration_date' => $motorhomeStockItem->registration_date,
            'registration_number' => $motorhomeStockItem->registration_number,
            'source' => $motorhomeStockItem->source,
            'transmission' => $motorhomeStockItem->transmission,
            'unique_code' => $motorhomeStockItem->unique_code,
            'mro' => $motorhomeStockItem->mro,
            'width' => $motorhomeStockItem->width,
            'year' => $motorhomeStockItem->year,
            'delivery_date' => $motorhomeStockItem->delivery_date,
            'features' => $motorhomeStockItem->features,
            'display_name' => $motorhomeStockItemPresenter->title(),
        ];
    }
}
