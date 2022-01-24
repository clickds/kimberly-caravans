<?php

use App\Models\Motorhome;
use Illuminate\Database\Seeder;

class MotorhomeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $m = factory(Motorhome::class)->states('with-images')->create();
        // Create others in the range
        factory(Motorhome::class)->states('with-images')->create([
            'motorhome_range_id' => $m->motorhome_range_id,
        ]);
    }
}
