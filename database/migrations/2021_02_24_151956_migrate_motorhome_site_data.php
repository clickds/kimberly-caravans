<?php

use App\Models\Motorhome;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class MigrateMotorhomeSiteData extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('pageable_site')->where('pageable_type', Motorhome::class)
            ->orderBy('pageable_id', 'asc')->chunk(100, function ($motorhomeSites) {
                foreach ($motorhomeSites as $motorhomeSite) {
                    try {
                        DB::table('motorhome_site')->insert([
                            'motorhome_id' => $motorhomeSite->pageable_id,
                            'site_id' => $motorhomeSite->site_id,
                            'price' => $motorhomeSite->price,
                            'recommended_price' => $motorhomeSite->recommended_price ?: null,
                        ]);
                    } catch (Throwable $e) {
                        // I have some site_pages where motorhomes have been deleted but the site page exists
                    }
                }
            });
        DB::table('pageable_site')->where('pageable_type', Motorhome::class)->delete();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::table('motorhome_site')->orderBy('motorhome_id', 'asc')->chunk(100, function ($motorhomeSites) {
            foreach ($motorhomeSites as $motorhomeSite) {
                DB::table('pageable_site')->insert([
                    'pageable_type' => Motorhome::class,
                    'pageable_id' => $motorhomeSite->motorhome_id,
                    'site_id' => $motorhomeSite->site_id,
                    'price' => $motorhomeSite->price,
                    'recommended_price' => $motorhomeSite->recommended_price,
                ]);
            }
        });
        DB::table('motorhome_site')->delete();
    }
}
