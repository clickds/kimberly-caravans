<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Page;
use App\Models\Site;
use App\Models\StockSearchLink;
use Faker\Generator as Faker;

$factory->define(StockSearchLink::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'type' => $faker->randomElement(StockSearchLink::TYPES),
        'site_id' => function () {
            return factory(Site::class)->create()->id;
        },
        'page_id' => function () {
            return factory(Page::class)->create()->id;
        },
    ];
});
