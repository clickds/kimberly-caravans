<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Brochure;
use App\Models\Site;
use Faker\Generator as Faker;

$factory->define(Brochure::class, function (Faker $faker) {
    return [
        'title' => $faker->sentence(),
        'site_id' => function () {
            return factory(Site::class)->create()->id;
        },
        'published_at' => now(),
    ];
});
