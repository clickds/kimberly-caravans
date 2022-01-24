<?php

use App\Models\BrochureGroup;
use Illuminate\Database\Seeder;

class BrochureGroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
		factory(BrochureGroup::class, 10)->create();
    }
}
