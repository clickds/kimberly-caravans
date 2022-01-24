<?php

use App\Models\MotorhomeStockItem;
use App\Models\Seat;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMotorhomeStockItemSeatTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('motorhome_stock_item_seat', function (Blueprint $table) {
            $table->foreignId('seat_id');
            $table->foreignId('motorhome_stock_item_id');

            $table->unique(['motorhome_stock_item_id', 'seat_id'], 'seat_motorhome_stock');

            $table->foreign('seat_id')->references('id')->on('seats');
            $table->foreign('motorhome_stock_item_id', 'motorhome_stock_seat_stock_id')->references('id')->on('motorhome_stock_items')->onDelete('cascade');
        });
        MotorhomeStockItem::chunk(200, function ($items) {
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
        Schema::table('motorhome_stock_item_seat', function (Blueprint $table) {
            $table->dropForeign(['seat_id']);
            $table->dropForeign('motorhome_stock_seat_stock_id');
        });
        Schema::dropIfExists('motorhome_stock_item_seat');
    }
}
