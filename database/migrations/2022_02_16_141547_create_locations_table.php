<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLocationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('locations', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('name',255)->nullable(false);
            $table->tinyText('address')->nullable(false);
            $table->string('contact_number',20)->nullable(true);
            $table->string('lng',50)->nullable(false);
            $table->string('lat',50)->nullable(false);
            $table->string('heading_1',255)->nullable(true);
            $table->text('content_1')->nullable(true);
            $table->string('heading_2',255)->nullable(true);
            $table->text('content_2')->nullable(true);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('locations');
    }
}
