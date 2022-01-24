<?php

namespace App\Services\Search\CaravanStockItem;

use App\Models\CaravanStockItem;
use App\Presenters\StockItem\CaravanPresenter;

final class DataProvider
{
    public static function getSearchIndexData(CaravanStockItem $caravanStockItem): array
    {
        $caravanStockItemPresenter = (new CaravanPresenter())->setWrappedObject($caravanStockItem);

        return [
            'stock_type' => $caravanStockItem->stockType(),
            'attention_grabber' => $caravanStockItem->attention_grabber,
            'axles' => $caravanStockItem->axles,
            'berths' => $caravanStockItem->berths->map(function ($item) {
                return $item->number;
            }),
            'condition' => $caravanStockItem->condition,
            'demonstrator' => $caravanStockItem->demonstrator,
            'description' => $caravanStockItem->description,
            'managers_special' => $caravanStockItem->managers_special,
            'height' => $caravanStockItem->height,
            'layout_name' => is_null($caravanStockItem->layout) ? '' : $caravanStockItem->layout->name,
            'length' => $caravanStockItem->length,
            'mtplm' => $caravanStockItem->mtplm,
            'number_of_owners' => $caravanStockItem->number_of_owners,
            'payload' => $caravanStockItem->payload,
            'price' => $caravanStockItem->price,
            'recommended_price' => $caravanStockItem->recommended_price,
            'registration_date' => $caravanStockItem->registration_date,
            'source' => $caravanStockItem->source,
            'unique_code' => $caravanStockItem->unique_code,
            'mro' => $caravanStockItem->mro,
            'width' => $caravanStockItem->width,
            'year' => $caravanStockItem->year,
            'delivery_date' => $caravanStockItem->delivery_date,
            'features' => $caravanStockItem->features,
            'display_name' => $caravanStockItemPresenter->title(),
        ];
    }
}
