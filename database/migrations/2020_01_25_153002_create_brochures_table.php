<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBrochuresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('brochures', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('site_id')->index();
            $table->unsignedBigInteger('group_id')->nullable(true);
            $table->string('title');
            $table->dateTime('published_at')->nullable(true);
            $table->dateTime('expired_at')->nullable(true);
            $table->timestamps();

            $table->foreign('group_id')->references('id')->on('brochure_groups')->onDelete('cascade');
            $table->foreign('site_id')->references('id')->on('sites');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('brochures', function (Blueprint $table) {
            $table->dropForeign(['group_id']);
            $table->dropForeign(['site_id']);
        });
        Schema::dropIfExists('brochures');
    }
}
