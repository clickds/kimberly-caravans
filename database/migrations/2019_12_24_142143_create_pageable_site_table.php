<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePageableSiteTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pageable_site', function (Blueprint $table) {
            $table->bigInteger('site_id')->unsigned()->index();
            $table->bigInteger('pageable_id')->unsigned();
            $table->string('pageable_type');
            $table->unsignedDecimal('price', 8, 2)->nullable();

            $table->index(['pageable_id', 'pageable_type']);
            $table->foreign('site_id')->references('id')->on('sites')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pageable_site', function (Blueprint $table) {
            $table->dropForeign(['site_id']);
        });
        Schema::dropIfExists('pageable_site');
    }
}
