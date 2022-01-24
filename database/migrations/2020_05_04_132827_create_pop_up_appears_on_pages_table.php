<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePopUpAppearsOnPagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pop_up_appears_on_pages', function (Blueprint $table) {
            $table->unsignedBigInteger('pop_up_id');
            $table->unsignedBigInteger('page_id');

            $table->unique(['pop_up_id', 'page_id']);
            $table->foreign('pop_up_id')->references('id')->on('pop_ups')->onDelete('cascade');
            $table->foreign('page_id')->references('id')->on('pages')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pop_up_appears_on_pages', function (Blueprint $table) {
            $table->dropForeign(['pop_up_id']);
            $table->dropForeign(['page_id']);
        });
        Schema::dropIfExists('pop_up_appears_on_pages');
    }
}
