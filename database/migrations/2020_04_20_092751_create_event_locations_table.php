<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEventLocationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('event_locations', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('address_line_1')->nullable();
            $table->text('address_line_2')->nullable();
            $table->string('town')->nullable();
            $table->string('county')->nullable();
            $table->string('postcode')->nullable();
            $table->string('phone')->nullable();
            $table->string('fax')->nullable();
            $table->float('latitude', 9, 6);
            $table->float('longitude', 9, 6);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('event_locations');
    }
}
