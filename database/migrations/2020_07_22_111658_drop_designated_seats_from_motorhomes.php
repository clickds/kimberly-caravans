<?php

use App\Models\Motorhome;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropDesignatedSeatsFromMotorhomes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('motorhomes', function (Blueprint $table) {
            $table->dropColumn('designated_seats');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('motorhomes', function (Blueprint $table) {
            $table->unsignedInteger('designated_seats')->index()->default(2);
        });
        Motorhome::chunk(200, function ($items) {
            foreach ($items as $item) {
                $item->designated_seats = $item->seats()->pluck('number')->first();
                $item->save();
            }
        });
    }
}
