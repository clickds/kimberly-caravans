<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Caravan;
use App\Models\CaravanStockItem;
use App\Models\CaravanStockItemFeature;
use App\Models\Layout;
use App\Models\Manufacturer;
use Faker\Generator as Faker;

$factory->define(CaravanStockItem::class, function (Faker $faker) {
    return [
        'axles' => $faker->randomElement(Caravan::AXLES),
        'caravan_id' => null,
        'condition' => $faker->randomElement(CaravanStockItem::CONDITIONS),
        'demonstrator' => false,
        'description' => $faker->paragraph(),
        'managers_special' => false,
        'height' => $faker->randomFloat(2, 0, 500),
        'layout_id' => function () {
            return factory(Layout::class)->create()->id;
        },
        'length' => $faker->randomFloat(2, 0, 500),
        'live' => true,
        'manufacturer_id' => function () {
            return factory(Manufacturer::class)->create()->id;
        },
        'dealer_id' => null,
        'mtplm' => $faker->randomNumber(),
        'model' => $faker->company,
        'number_of_owners' => $faker->randomDigitNotNull,
        'payload' => $faker->randomNumber(),
        'price' => $faker->randomFloat(2, 0, 500000),
        'recommended_price' => $faker->randomFloat(2, 0, 500000),
        'registration_date' => $faker->date(),
        'source' => $faker->randomElement(CaravanStockItem::SOURCES),
        'unique_code' => $faker->unique()->ean8,
        'mro' => $faker->randomNumber(),
        'width' => $faker->randomFloat(2, 0, 500),
        'year' => $faker->year(),
        'delivery_date' => $faker->date(),
        'reduced_price_start_date' => $faker->date(),
    ];
});

$factory->state(CaravanStockItem::class, 'from-caravan', [
    'caravan_id' => function () {
        return factory(Caravan::class)->create()->id;
    },
    'condition' => CaravanStockItem::NEW_CONDITION,
    'source' => CaravanStockItem::NEW_PRODUCT_SOURCE,
]);

$factory->state(CaravanStockItem::class, 'from-feed', [
    'caravan_id' => null,
    'condition' => CaravanStockItem::USED_CONDITION,
    'source' => CaravanStockItem::FEED_SOURCE,
]);

$factory->define(CaravanStockItemFeature::class, function (Faker $faker) {
    return [
        'caravan_stock_item_id' => function () {
            return factory(CaravanStockItem::class)->create()->id;
        },
        'name' => $faker->sentence(),
    ];
});
