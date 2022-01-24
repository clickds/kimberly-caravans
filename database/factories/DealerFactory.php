<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Dealer;
use App\Models\Site;
use Faker\Generator as Faker;

$factory->define(Dealer::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'feed_location_code' => $faker->randomLetter . $faker->randomLetter,
        'is_branch' => $faker->boolean(),
        'is_servicing_center' => $faker->boolean(),
        'can_view_motorhomes' => $faker->boolean(),
        'can_view_caravans' => $faker->boolean(),
        'site_id' => function () {
            return factory(Site::class)->create()->id;
        },
        'position' => 0,
        'video_embed_code' => null,
        'website_url' => $faker->url,
        'website_link_text' => $faker->company . 'website',
        'facilities' => $faker->paragraph(),
        'servicing_centre' => $faker->paragraph(),
        'published_at' => now(),
        'expired_at' => null,
    ];
});
