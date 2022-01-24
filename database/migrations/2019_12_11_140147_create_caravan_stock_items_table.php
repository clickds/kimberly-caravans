<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCaravanStockItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('caravan_stock_items', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('caravan_id')->nullable()->index();
            $table->unsignedBigInteger('manufacturer_id')->index();
            $table->string('model');
            $table->unsignedBigInteger('layout_id')->index();
            $table->string('condition');
            $table->string('unique_code')->unique()->nullable();
            $table->string('axles')->index();
            $table->unsignedDecimal('width', 8, 2);
            $table->unsignedDecimal('height', 8, 2);
            $table->unsignedDecimal('length', 8, 2)->index();
            $table->unsignedInteger('mro');
            $table->unsignedInteger('mtplm')->index();
            $table->unsignedInteger('payload');
            $table->unsignedInteger('berths')->index();
            $table->unsignedInteger('year');
            $table->unsignedDecimal('recommended_price', 8, 2);
            $table->unsignedDecimal('price', 8, 2);
            $table->string('source');
            $table->boolean('demonstrator')->default(false);
            $table->date('registration_date')->nullable();
            $table->text('description');
            $table->unsignedInteger('number_of_owners')->default(0);
            $table->boolean('exclusive')->default(false);
            $table->timestamps();

            $table->foreign('layout_id')->references('id')->on('layouts');
            $table->foreign('caravan_id')->references('id')->on('caravans')->onDelete('cascade');
            $table->foreign('manufacturer_id')->references('id')->on('manufacturers');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('caravan_stock_items', function (Blueprint $table) {
            $table->dropForeign(['caravan_id']);
            $table->dropForeign(['layout_id']);
            $table->dropForeign(['manufacturer_id']);
        });
        Schema::dropIfExists('caravan_stock_items');
    }
}
