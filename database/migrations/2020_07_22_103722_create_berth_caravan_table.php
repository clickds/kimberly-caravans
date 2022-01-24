<?php

use App\Models\Berth;
use App\Models\Caravan;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBerthCaravanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('berth_caravan', function (Blueprint $table) {
            $table->foreignId('berth_id');
            $table->foreignId('caravan_id');

            $table->unique(['caravan_id', 'berth_id']);

            $table->foreign('berth_id')->references('id')->on('berths');
            $table->foreign('caravan_id')->references('id')->on('caravans')->onDelete('cascade');
        });
        Caravan::chunk(200, function ($caravans) {
            foreach ($caravans as $caravan) {
                $berth = Berth::firstOrCreate(['number' => $caravan->berths]);
                $caravan->berths()->attach($berth);
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
        Schema::table('berth_caravan', function (Blueprint $table) {
            $table->dropForeign(['berth_id']);
            $table->dropForeign(['caravan_id']);
        });
        Schema::dropIfExists('berth_caravans');
    }
}
