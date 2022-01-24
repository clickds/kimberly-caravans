<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Page;
use App\Models\Site;
use Faker\Generator as Faker;

$factory->define(Page::class, function (Faker $faker) {
    return [
        'site_id' => function () {
            return factory(Site::class)->state('default')->create()->id;
        },
        'name' => $faker->sentence(),
        'meta_title' => $faker->sentence(),
        'meta_description' => $faker->paragraph(),
        'template' => array_keys(Page::STANDARD_TEMPLATES)[0],
        'variety' => $faker->randomElement(Page::VARIETIES),
        'parent_id' => null,
        'live' => true,
        'published_at' => null,
        'expired_at' => null,
    ];
});

$factory->state(Page::class, 'standard-template', function (Faker $faker) {
    return [
        'template' => Page::TEMPLATE_STANDARD,
    ];
});
