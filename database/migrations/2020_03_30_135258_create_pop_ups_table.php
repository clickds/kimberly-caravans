<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePopUpsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pop_ups', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('site_id')->index();
            $table->string('name');
            $table->string('external_url')->nullable();
            $table->unsignedBigInteger('page_id')->nullable();
            $table->boolean('live')->default(true);
            $table->dateTime('published_at')->nullable();
            $table->dateTime('expired_at')->nullable();

            $table->timestamps();

            $table->index(['live', 'published_at', 'expired_at']);
            $table->foreign('page_id')->references('id')->on('pages')->onDelete('cascade');
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
        Schema::table('pop_ups', function (Blueprint $table) {
            $table->dropForeign(['page_id']);
            $table->dropForeign(['site_id']);
        });
        Schema::dropIfExists('pop_ups');
    }
}
