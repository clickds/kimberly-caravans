<?php

use App\Models\CaravanRange;
use Illuminate\Database\Seeder;

class CaravanRangeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(CaravanRange::class, 1)->create();
    }
}
