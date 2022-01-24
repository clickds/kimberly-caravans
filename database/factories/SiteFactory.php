<?php

use App\Models\Site;
use Faker\Generator as Faker;

$factory->define(Site::class, function (Faker $faker) {
    return [
        'country' => "{$faker->unique()->country} {$faker->unique()->randomNumber}",
        'subdomain' => "{$faker->unique()->languageCode}-{$faker->unique()->randomNumber}",
        'flag' => 'england.svg',
        'is_default' => false,
        'has_stock' => false,
        'show_opening_times_and_telephone_number' => $faker->boolean(),
        'display_exclusive_manufacturers_separately' => $faker->boolean(),
        'show_buy_tab_on_new_model_pages' => $faker->boolean(),
        'show_offers_tab_on_new_model_pages' => $faker->boolean(),
        'show_live_chat' => $faker->boolean(),
        'show_social_icons' => $faker->boolean(),
        'show_accreditation_icons' => $faker->boolean(),
        'show_footer_content' => $faker->boolean(),
        'show_dealer_ranges' => $faker->boolean(),
        'phone_number' => $faker->phoneNumber,
        'timezone' => 'Europe/London',
        'campaign_monitor_list_id' => null,
    ];
});

$factory->state(Site::class, 'default', [
    'is_default' => true,
]);

$factory->state(Site::class, 'has-stock', [
    'has_stock' => true,
]);
