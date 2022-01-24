<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateButtonsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('buttons', function (Blueprint $table) {
            $table->id();
            $table->string('buttonable_type');
            $table->unsignedBigInteger('buttonable_id');
            $table->unsignedBigInteger('link_page_id')->nullable();
            $table->string('name');
            $table->string('external_url')->nullable();
            $table->string('colour');
            $table->integer('position')->index()->default(0);
            $table->timestamps();

            $table->index(['buttonable_id', 'buttonable_type']);
            $table->foreign('link_page_id')->references('id')->on('pages')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('buttons', function (Blueprint $table) {
            $table->dropForeign(['link_page_id']);
        });
        Schema::dropIfExists('buttons');
    }
}
