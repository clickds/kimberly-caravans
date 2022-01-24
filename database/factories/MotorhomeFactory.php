<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\{
    Layout,
    Motorhome,
    MotorhomeRange
};
use Faker\Generator as Faker;

$factory->define(Motorhome::class, function (Faker $faker) {
    return [
        'chassis_manufacturer' => $faker->company,
        'conversion' => $faker->randomElement(Motorhome::CONVERSIONS),
        'description' => $faker->paragraph(),
        'engine_size' => $faker->randomNumber() . 'cc',
        'engine_power' => $faker->randomNumber() . 'cc',
        'exclusive' => false,
        'fuel' => $faker->randomElement(Motorhome::FUELS),
        'height_includes_aerial' => false,
        'height' => $faker->randomFloat(2, 0, 500),
        'layout_id' => function () {
            return factory(Layout::class)->create()->id;
        },
        'length' => $faker->randomFloat(2, 0, 500),
        'mtplm' => $faker->randomNumber(),
        'motorhome_range_id' => function () {
            return factory(MotorhomeRange::class)->create()->id;
        },
        'name' => $faker->company,
        'payload' => $faker->randomNumber(),
        'position' => $faker->randomDigitNotNull,
        'small_print' => $faker->paragraph(),
        'transmission' => $faker->randomElement(Motorhome::TRANSMISSIONS),
        'mro' => $faker->randomNumber(),
        'width' => $faker->randomFloat(2, 0, 500),
        'year' => $faker->year(),
        'live' => true,
    ];
});

$factory->state(Motorhome::class, 'with-images', []);
$factory->afterCreatingState(Motorhome::class, 'with-images', function ($motorhome, $faker) {
    $mainImageUrl = $faker->imageUrl(1920, 480, null, false);
    $dayFloorplanUrl = $faker->imageUrl(640, 480, null, false);
    $nightFloorplanUrl = $faker->imageUrl(640, 480, null, false);

    $motorhome->addMediaFromUrl($mainImageUrl)->toMediaCollection('mainImage');
    $motorhome->addMediaFromUrl($dayFloorplanUrl)->toMediaCollection('dayFloorPlan');
    $motorhome->addMediaFromUrl($nightFloorplanUrl)->toMediaCollection('nightFloorPlan');
});
