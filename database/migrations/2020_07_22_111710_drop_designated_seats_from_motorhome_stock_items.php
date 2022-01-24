<?php

use App\Models\MotorhomeStockItem;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropDesignatedSeatsFromMotorhomeStockItems extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('motorhome_stock_items', function (Blueprint $table) {
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
        Schema::table('motorhome_stock_items', function (Blueprint $table) {
            $table->unsignedInteger('designated_seats')->index()->default(2);
        });
        MotorhomeStockItem::chunk(200, function ($items) {
            foreach ($items as $item) {
                MotorhomeStockItem::chunk(200, function ($items) {
                    foreach ($items as $item) {
                        $item->berths = $item->berths()->pluck('number')->first();
                        $item->save();
                    }
                });
                $item->save();
            }
        });
    }
}
