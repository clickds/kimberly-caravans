<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Review;
use Faker\Generator as Faker;

$factory->define(Review::class, function (Faker $faker) {
    return [
        'date' => $faker->date(),
        'magazine' => $faker->company,
        'title' => $faker->sentence,
        'link' => $faker->url,
        'text' => $faker->paragraph,
        'position' => 0,
        'published_at' => null,
        'expired_at' => null,
    ];
});
