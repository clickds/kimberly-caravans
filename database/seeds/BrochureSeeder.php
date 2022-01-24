<?php

use App\Models\Brochure;
use Illuminate\Database\Seeder;

class BrochureSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(Brochure::class, 5)->create();
    }
}
