<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pages', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('site_id');
            $table->unsignedBigInteger('parent_id')->nullable(true);
            $table->string('name');
            $table->string('slug');
            $table->string('template')->nullable(false)->default("standard");
            $table->timestamp('published_at')->nullable(true);
            $table->timestamp('expired_at')->nullable(true);
            $table->string('variety');
            $table->boolean('live')->default(true);
            $table->string('pageable_type')->nullable(true);
            $table->bigInteger('pageable_id')->nullable(true);
            $table->timestamps();

            $table->index(['published_at', 'expired_at', 'live']);
            $table->index(['pageable_type', 'pageable_id']);
            $table->foreign('parent_id')->references('id')->on('pages')->onDelete('set null');
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
        Schema::dropIfExists('pages');
    }
}
