<?php

use App\Models\Motorhome;
use App\Models\Seat;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMotorhomeSeatTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('motorhome_seat', function (Blueprint $table) {
            $table->foreignId('seat_id');
            $table->foreignId('motorhome_id');

            $table->unique(['motorhome_id', 'seat_id']);

            $table->foreign('seat_id')->references('id')->on('seats');
            $table->foreign('motorhome_id')->references('id')->on('motorhomes')->onDelete('cascade');
        });
        Motorhome::chunk(200, function ($items) {
            foreach ($items as $item) {
                $seat = Seat::firstOrCreate(['number' => $item->designated_seats]);
                $item->seats()->attach($seat);
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
        Schema::table('motorhome_seat', function (Blueprint $table) {
            $table->dropForeign(['seat_id']);
            $table->dropForeign(['motorhome_id']);
        });
        Schema::dropIfExists('motorhome_seat');
    }
}
