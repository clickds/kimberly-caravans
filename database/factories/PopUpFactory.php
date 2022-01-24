<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\PopUp;
use App\Models\Site;
use Faker\Generator as Faker;

$factory->define(PopUp::class, function (Faker $faker) {
    return [
        'external_url' => 'https://www.google.co.uk',
        'page_id' => null,
        'site_id' => function () {
            return factory(Site::class)->create()->id;
        },
        'name' => $faker->name,
        'live' => true,
        'published_at' => null,
        'expired_at' => null,
        'appears_on_all_pages' => false,
        'appears_on_new_motorhome_pages' => false,
        'appears_on_new_caravan_pages' => false,
        'appears_on_used_motorhome_pages' => false,
        'appears_on_used_caravan_pages' => false,
    ];
});
