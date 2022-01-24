<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Alias;
use App\Models\Page;
use App\Models\Site;
use Faker\Generator as Faker;

$factory->define(Alias::class, function (Faker $faker) {
    return [
        'capture_path' => '/' . $faker->unique()->slug,
        'page_id' => function () {
            return factory(Page::class)->create()->id;
        },
        'site_id' => function () {
            return factory(Site::class)->create()->id;
        },
    ];
});
