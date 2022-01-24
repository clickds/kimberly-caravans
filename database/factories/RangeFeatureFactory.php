<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\CaravanRange;
use App\Models\MotorhomeRange;
use App\Models\RangeFeature;
use App\Models\Site;
use Faker\Generator as Faker;

$factory->define(RangeFeature::class, function (Faker $faker) {
    return [
        'vehicle_range_type' => null,
        'vehicle_range_id' => null,
        'content' => $faker->paragraph(),
        'key' => false,
        'name' => $faker->name,
        'position' => 0,
        'warranty' => 0,
    ];
});

$factory->state(RangeFeature::class, 'caravan-range', function (Faker $faker) {
    $range = factory(CaravanRange::class)->create();
    return [
        'vehicle_range_type' => CaravanRange::class,
        'vehicle_range_id' => $range->id,
    ];
});

$factory->state(RangeFeature::class, 'motorhome-range', function (Faker $faker) {
    $range = factory(MotorhomeRange::class)->create();
    return [
        'vehicle_range_type' => MotorhomeRange::class,
        'vehicle_range_id' => $range->id,
    ];
});
