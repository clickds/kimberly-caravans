<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAreasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('areas', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('page_id');
            $table->string('name');
            $table->string('holder');
            $table->unsignedInteger('columns');
            $table->unsignedInteger('position')->default(0);
            $table->string('heading')->nullable(true);
            $table->string('width');
            $table->string('background_colour');
            $table->timestamp('published_at')->nullable(true);
            $table->timestamp('expired_at')->nullable(true);
            $table->boolean('live')->default(true);
            $table->timestamps();

            $table->index(['published_at', 'expired_at', 'live']);
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
        Schema::dropIfExists('areas');
    }
}
