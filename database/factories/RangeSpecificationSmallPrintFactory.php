<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\CaravanRange;
use App\Models\MotorhomeRange;
use App\Models\RangeSpecificationSmallPrint;
use App\Models\Site;
use Faker\Generator as Faker;

$factory->define(RangeSpecificationSmallPrint::class, function (Faker $faker) {
    return [
        'vehicle_range_type' => null,
        'vehicle_range_id' => null,
        'content' => $faker->paragraph(),
        'name' => $faker->name,
        'position' => 0,
        'site_id' => function () {
            return factory(Site::class)->create()->id;
        },
    ];
});

$factory->state(RangeSpecificationSmallPrint::class, 'caravan-range', function (Faker $faker) {
    $range = factory(CaravanRange::class)->create();
    return [
        'vehicle_range_type' => CaravanRange::class,
        'vehicle_range_id' => $range->id,
    ];
});

$factory->state(RangeSpecificationSmallPrint::class, 'motorhome-range', function (Faker $faker) {
    $range = factory(MotorhomeRange::class)->create();
    return [
        'vehicle_range_type' => MotorhomeRange::class,
        'vehicle_range_id' => $range->id,
    ];
});
