<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNavigationItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('navigation_items', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('navigation_id');
            $table->unsignedBigInteger('parent_id')->nullable(true);
            $table->string('name');
            $table->unsignedBigInteger('page')->nullable(true);
            $table->unsignedBigInteger('internal_id');
            $table->string('url');
            $table->timestamps();

            $table->foreign('navigation_id')->references('id')->on('navigations');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('navigation_items');
    }
}
