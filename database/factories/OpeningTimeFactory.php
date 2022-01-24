<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\OpeningTime;
use App\Models\Site;
use Carbon\Carbon;
use Faker\Generator as Faker;

$factory->define(OpeningTime::class, function (Faker $faker) {
    return [
        'site_id' => function () {
            return factory(Site::class)->create()->id;
        },
        'day' => $faker->unique()->randomElement(Carbon::getDays()),
        'opens_at' => $faker->time(),
        'closes_at' => $faker->time(),
        'closed' => false,
    ];
});
