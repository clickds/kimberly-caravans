<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\SpecialOffer;
use Faker\Generator as Faker;

$factory->define(SpecialOffer::class, function (Faker $faker) {
    return [
        'live' => true,
        'content' => $faker->paragraph(),
        'name' => $faker->name,
        'type' => $faker->randomElement(SpecialOffer::TYPES),
        'offer_type' => $faker->randomElement(SpecialOffer::OFFER_TYPES),
        'link_used_caravan_stock' => true,
        'link_used_motorhome_stock' => true,
        'link_managers_special_stock' => true,
        'link_on_sale_stock' => true,
        'stock_bar_colour' => $faker->randomElement(array_keys(SpecialOffer::STOCK_BAR_COLOURS)),
        'stock_bar_text_colour' => $faker->randomElement(array_keys(SpecialOffer::STOCK_BAR_TEXT_COLOURS)),
    ];
});
