<?php

use App\Models\Berth;
use App\Models\Motorhome;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBerthMotorhomeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('berth_motorhome', function (Blueprint $table) {
            $table->foreignId('berth_id');
            $table->foreignId('motorhome_id');

            $table->unique(['motorhome_id', 'berth_id']);

            $table->foreign('berth_id')->references('id')->on('berths');
            $table->foreign('motorhome_id')->references('id')->on('motorhomes')->onDelete('cascade');
        });

        Motorhome::chunk(200, function ($motorhomes) {
            foreach ($motorhomes as $motorhome) {
                $berth = Berth::firstOrCreate(['number' => $motorhome->berths]);
                $motorhome->berths()->attach($berth);
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('berth_motorhome', function (Blueprint $table) {
            $table->dropForeign(['berth_id']);
            $table->dropForeign(['motorhome_id']);
        });
        Schema::dropIfExists('berth_motorhome');
    }
}
