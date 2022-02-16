<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('news', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('title',255)->nullable(false);
            $table->string('slug',255)->nullable(false);
            $table->text('content');
            $table->boolean('published')->default(true);
            $table->unsignedBigInteger('author_id')->nullable(false);


        });

        // add foreign keys
        Schema::table('news',function(Blueprint $table) {

            // add foreign keys
            $table->foreign('author_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('news');
    }
}
