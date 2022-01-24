<?php

use App\Models\MotorhomeRange;
use Illuminate\Database\Seeder;

class MotorhomeRangeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(MotorhomeRange::class, 1)->create();
    }
}
