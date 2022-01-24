<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMotorhomeStockItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('motorhome_stock_items', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('motorhome_id')->nullable()->index();
            $table->unsignedBigInteger('manufacturer_id')->index();
            $table->unsignedBigInteger('layout_id')->index();
            $table->string('model');
            $table->string('condition');
            $table->string('unique_code')->unique()->nullable();
            $table->unsignedDecimal('width', 8, 2);
            $table->unsignedDecimal('height', 8, 2);
            $table->unsignedDecimal('length', 8, 2)->index();
            $table->unsignedInteger('mro');
            $table->unsignedInteger('mtplm')->index();
            $table->unsignedInteger('payload');
            $table->unsignedInteger('berths')->index();
            $table->string('engine_size')->nullable();
            $table->string('engine_power')->nullable();
            $table->string('engine_torque')->nullable();
            $table->unsignedInteger('designated_seats')->index();
            $table->string('transmission')->index();
            $table->string('chassis_manufacturer')->index();
            $table->string('fuel');
            $table->string('conversion')->index();
            $table->unsignedInteger('mileage')->default(0);
            $table->unsignedInteger('year');
            $table->unsignedDecimal('recommended_price', 8, 2);
            $table->unsignedDecimal('price', 8, 2);
            $table->string('source');
            $table->boolean('demonstrator')->default(false);
            $table->date('registration_date')->nullable();
            $table->string('registration_number')->nullable();
            $table->text('description');
            $table->unsignedInteger('number_of_owners')->default(0);
            $table->boolean('exclusive')->default(false);
            $table->timestamps();

            $table->foreign('layout_id')->references('id')->on('layouts');
            $table->foreign('motorhome_id')->references('id')->on('motorhomes')->onDelete('cascade');
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
        Schema::table('motorhome_stock_items', function (Blueprint $table) {
            $table->dropForeign(['motorhome_id']);
            $table->dropForeign(['layout_id']);
            $table->dropForeign(['manufacturer_id']);
        });
        Schema::dropIfExists('motorhome_stock_items');
    }
}
