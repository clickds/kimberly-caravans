<?php

use App\Models\Caravan;
use Illuminate\Database\Seeder;

class CaravanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $c = factory(Caravan::class)->states('with-images')->create();
        // Create others in the range
        factory(Caravan::class)->states('with-images')->create([
            'caravan_range_id' => $c->caravan_range_id,
        ]);
    }
}
