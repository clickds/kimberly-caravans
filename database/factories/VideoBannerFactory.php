<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\VideoBanner;
use Faker\Generator as Faker;

$factory->define(VideoBanner::class, function (Faker $faker) {
    return [
        'name' => $faker->unique()->name,
        'published_at' => null,
        'expired_at' => null,
        'live' => true,
    ];
});
