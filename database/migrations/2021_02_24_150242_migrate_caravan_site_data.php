<?php

use App\Models\Caravan;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class MigrateCaravanSiteData extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('pageable_site')->where('pageable_type', Caravan::class)
            ->orderBy('pageable_id', 'asc')->chunk(100, function ($caravanSites) {
                foreach ($caravanSites as $caravanSite) {
                    try {
                        DB::table('caravan_site')->insert([
                            'caravan_id' => $caravanSite->pageable_id,
                            'site_id' => $caravanSite->site_id,
                            'price' => $caravanSite->price,
                            'recommended_price' => $caravanSite->recommended_price ?: null,
                        ]);
                    } catch (Throwable $e) {
                        // I have some site_pages where caravans have been deleted but the site page exists
                    }
                }
            });
        DB::table('pageable_site')->where('pageable_type', Caravan::class)->delete();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::table('caravan_site')->orderBy('caravan_id', 'asc')->chunk(100, function ($caravanSites) {
            foreach ($caravanSites as $caravanSite) {
                DB::table('pageable_site')->insert([
                    'pageable_type' => Caravan::class,
                    'pageable_id' => $caravanSite->caravan_id,
                    'site_id' => $caravanSite->site_id,
                    'price' => $caravanSite->price,
                    'recommended_price' => $caravanSite->recommended_price,
                ]);
            }
        });
        DB::table('caravan_site')->delete();
    }
}
