<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPageIdAndExternalUrlToPanelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('panels', function (Blueprint $table) {
            $table->string('external_url')->nullable();
            $table->unsignedBigInteger('page_id')->nullable();

            $table->foreign('page_id')->references('id')->on('pages')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('panels', function (Blueprint $table) {
            $table->dropForeign(['page_id']);

            $table->dropColumn('external_url');
            $table->dropColumn('page_id');
        });
    }
}
