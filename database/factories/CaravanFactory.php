<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\{
    Caravan,
    CaravanRange,
    Layout
};
use Faker\Generator as Faker;

$factory->define(Caravan::class, function (Faker $faker) {
    return [
        'axles' => $faker->randomElement(Caravan::AXLES),
        'caravan_range_id' => function () {
            return factory(CaravanRange::class)->create()->id;
        },
        'description' => $faker->paragraph(),
        'exclusive' => false,
        'height_includes_aerial' => false,
        'height' => $faker->randomFloat(2, 0, 500),
        'layout_id' => function () {
            return factory(Layout::class)->create()->id;
        },
        'length' => $faker->randomFloat(2, 0, 500),
        'mtplm' => $faker->randomNumber(),
        'name' => $faker->company,
        'payload' => $faker->randomNumber(),
        'position' => $faker->randomDigitNotNull,
        'small_print' => $faker->paragraph(),
        'mro' => $faker->randomNumber(),
        'width' => $faker->randomFloat(2, 0, 500),
        'year' => $faker->year(),
        'live' => true,
    ];
});

$factory->state(Caravan::class, 'with-images', []);
$factory->afterCreatingState(Caravan::class, 'with-images', function ($motorhome, $faker) {
    $mainImageUrl = $faker->imageUrl(1920, 480, null, false);
    $dayFloorplanUrl = $faker->imageUrl(640, 480, null, false);
    $nightFloorplanUrl = $faker->imageUrl(640, 480, null, false);

    $motorhome->addMediaFromUrl($mainImageUrl)->toMediaCollection('mainImage');
    $motorhome->addMediaFromUrl($dayFloorplanUrl)->toMediaCollection('dayFloorPlan');
    $motorhome->addMediaFromUrl($nightFloorplanUrl)->toMediaCollection('nightFloorPlan');
});
