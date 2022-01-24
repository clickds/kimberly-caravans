<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Layout;
use App\Models\Manufacturer;
use App\Models\Motorhome;
use App\Models\MotorhomeStockItem;
use App\Models\MotorhomeStockItemFeature;
use Faker\Generator as Faker;

$factory->define(MotorhomeStockItem::class, function (Faker $faker) {
    return [
        'motorhome_id' => null,
        'condition' => $faker->randomElement(MotorhomeStockItem::CONDITIONS),
        'conversion' => $faker->randomElement(Motorhome::CONVERSIONS),
        'chassis_manufacturer' => $faker->company,
        'demonstrator' => false,
        'description' => $faker->paragraph(),
        'engine_size' => $faker->randomNumber() . 'L',
        'engine_power' => $faker->randomNumber() . 'cc',
        'managers_special' => false,
        'fuel' => $faker->randomElement(Motorhome::FUELS),
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
        'registration_number' => $faker->word(),
        'source' => $faker->randomElement(MotorhomeStockItem::SOURCES),
        'transmission' => $faker->randomElement(Motorhome::TRANSMISSIONS),
        'unique_code' => $faker->unique()->ean8,
        'mro' => $faker->randomNumber(),
        'width' => $faker->randomFloat(2, 0, 500),
        'year' => $faker->year(),
        'delivery_date' => $faker->date(),
        'reduced_price_start_date' => $faker->date(),
    ];
});

$factory->state(MotorhomeStockItem::class, 'from-motorhome', [
    'motorhome_id' => function () {
        return factory(Motorhome::class)->create()->id;
    },
    'condition' => MotorhomeStockItem::NEW_CONDITION,
    'source' => MotorhomeStockItem::NEW_PRODUCT_SOURCE,
]);

$factory->state(MotorhomeStockItem::class, 'from-feed', [
    'motorhome_id' => null,
    'condition' => MotorhomeStockItem::USED_CONDITION,
    'source' => MotorhomeStockItem::FEED_SOURCE,
]);


$factory->define(MotorhomeStockItemFeature::class, function (Faker $faker) {
    return [
        'motorhome_stock_item_id' => function () {
            return factory(MotorhomeStockItem::class)->create()->id;
        },
        'name' => $faker->sentence(),
    ];
});
