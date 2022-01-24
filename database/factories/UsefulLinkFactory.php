<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\UsefulLink;
use App\Models\UsefulLinkCategory;
use Faker\Generator as Faker;

$factory->define(UsefulLink::class, function (Faker $faker) {
    return [
        'useful_link_category_id' => function () {
            return factory(UsefulLinkCategory::class)->create()->id;
        },
        'name' => $faker->name,
        'url' => $faker->url,
        'position' => 0,
    ];
});
