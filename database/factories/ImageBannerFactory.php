<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\ImageBanner;
use Faker\Generator as Faker;

$factory->define(ImageBanner::class, function (Faker $faker) {
    return [
        'title' => $faker->name,
        'title_background_colour' => $faker->randomElement(array_keys(ImageBanner::BACKGROUND_COLOURS)),
        'title_text_colour' => $faker->randomElement(array_keys(ImageBanner::TEXT_COLOURS)),
        'content' => $faker->paragraph,
        'content_background_colour' => $faker->randomElement(array_keys(ImageBanner::BACKGROUND_COLOURS)),
        'content_text_colour' => $faker->randomElement(array_keys(ImageBanner::TEXT_COLOURS)),
        'position' => 0,
        'published_at' => null,
        'expired_at' => null,
        'live' => true,
    ];
});
