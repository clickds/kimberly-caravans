<?php

use App\Models\Site;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        factory(User::class, 1)->states('super')->create([
            'email' => 'dev@clickdigitalsolutions.co.uk',
        ]);
        factory(User::class, 10)->create();

        factory(Site::class, 1)->states(['default', 'has-stock'])->create();
        factory(Site::class, 4)->create();

        $this->call([
            TestPageSeeder::class,
            ManufacturerSeeder::class,
            MotorhomeRangeSeeder::class,
            CaravanRangeSeeder::class,
            LayoutSeeder::class,
            NavigationSeeder::class,
            NavigationItemSeeder::class,
            StockItemsSeeder::class,
            CtaSeeder::class,
            BrochureGroupSeeder::class,
            BrochureSeeder::class,
            FormSeeder::class,
        ]);
    }
}
