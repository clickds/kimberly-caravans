<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsefulLinksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('useful_links', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('useful_link_category_id')->index();
            $table->string('name');
            $table->string('url');
            $table->integer('position')->default(0);
            $table->timestamps();

            $table->foreign('useful_link_category_id')->references('id')
                ->on('useful_link_categories')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('useful_links', function (Blueprint $table) {
            $table->dropForeign(['useful_link_category_id']);
        });
        Schema::dropIfExists('useful_links');
    }
}
