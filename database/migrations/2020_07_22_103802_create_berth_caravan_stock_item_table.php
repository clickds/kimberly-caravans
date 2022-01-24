<?php

use App\Models\Berth;
use App\Models\CaravanStockItem;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBerthCaravanStockItemTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('berth_caravan_stock_item', function (Blueprint $table) {
            $table->foreignId('berth_id');
            $table->foreignId('caravan_stock_item_id');

            $table->unique(['caravan_stock_item_id', 'berth_id']);

            $table->foreign('berth_id')->references('id')->on('berths');
            $table->foreign('caravan_stock_item_id')->references('id')->on('caravan_stock_items')->onDelete('cascade');
        });

        CaravanStockItem::chunk(200, function ($items) {
            foreach ($items as $item) {
                $berth = Berth::firstOrCreate(['number' => $item->berths]);
                $item->berths()->attach($berth);
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
        Schema::table('berth_caravan_stock_item', function (Blueprint $table) {
            $table->dropForeign(['berth_id']);
            $table->dropForeign(['caravan_stock_item_id']);
        });
        Schema::dropIfExists('berth_caravan_stock_item');
    }
}
