<?php

use App\Models\Berth;
use App\Models\MotorhomeStockItem;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBerthMotorhomeStockItemTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('berth_motorhome_stock_item', function (Blueprint $table) {
            $table->foreignId('berth_id');
            $table->foreignId('motorhome_stock_item_id');

            $table->unique(['motorhome_stock_item_id', 'berth_id'], 'berth_motorhome_stock');

            $table->foreign('berth_id')->references('id')->on('berths');
            $table->foreign('motorhome_stock_item_id', 'motorhome_stock_berth_stock_id')->references('id')->on('motorhome_stock_items')->onDelete('cascade');
        });
        MotorhomeStockItem::chunk(200, function ($items) {
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
        Schema::table('berth_motorhome_stock_item', function (Blueprint $table) {
            $table->dropForeign(['berth_id']);
            $table->dropForeign('motorhome_stock_berth_stock_id');
        });
        Schema::dropIfExists('berth_motorhome_stock_item');
    }
}
