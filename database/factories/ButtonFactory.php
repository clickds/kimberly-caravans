<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Button;
use App\Models\ImageBanner;
use App\Models\SpecialOffer;
use Faker\Generator as Faker;

$factory->define(Button::class, function (Faker $faker) {
    return [
        'colour' => $faker->randomElement(array_keys(Button::COLOURS)),
        'external_url' => 'https://www.google.co.uk',
        'link_page_id' => null,
        'name' => $faker->name,
        'position' => 0,
        'buttonable_type' => null,
        'buttonable_id' => null,
    ];
});

$factory->state(Button::class, 'image-banner', function (Faker $faker) {
    $buttonable = factory(ImageBanner::class)->create();
    return [
        'buttonable_type' => ImageBanner::class,
        'buttonable_id' => $buttonable->id,
    ];
});
