<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateImageBannersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('image_banners', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('title_background_colour')->nullable();
            $table->string('title_text_colour');
            $table->text('content')->nullable();
            $table->string('content_background_colour')->nullable();
            $table->string('content_text_colour');
            $table->integer('position')->default(0);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('image_banners');
    }
}
