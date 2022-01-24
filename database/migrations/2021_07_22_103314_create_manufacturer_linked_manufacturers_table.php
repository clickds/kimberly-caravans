<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateManufacturerLinkedManufacturersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('manufacturer_linked_manufacturers', function (Blueprint $table) {
            $table->unsignedBigInteger('manufacturer_id');
            $table->unsignedBigInteger('linked_manufacturer_id');

            $table->primary(['manufacturer_id', 'linked_manufacturer_id'], 'mlm_manufacturer_id_linked_manufacturer_id_primary');
            $table->foreign('manufacturer_id')->references('id')->on('manufacturers');
            $table->foreign('linked_manufacturer_id')->references('id')->on('manufacturers');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('manufacturer_linked_manufacturers');
    }
}
