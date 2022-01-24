<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Logo;
use Faker\Generator as Faker;

$factory->define(Logo::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'external_url' => $faker->url,
        'page_id' => null,
        'display_location' => $faker->randomElement(Logo::VALID_DISPLAY_LOCATIONS),
    ];
});
