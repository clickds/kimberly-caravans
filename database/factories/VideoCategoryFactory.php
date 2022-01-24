<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\VideoCategory;
use Faker\Generator as Faker;

$factory->define(VideoCategory::class, function (Faker $faker) {
    return [
        'name' => $faker->unique()->name,
    ];
});
