<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFieldsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fields', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('fieldset_id')->index();
            $table->string('name');
            $table->string('label');
            $table->string('input_name');
            $table->string('type');
            $table->boolean('required')->default(false);
            $table->integer('position')->default(0);
            $table->text('options')->nullable();
            $table->timestamps();

            $table->foreign('fieldset_id')->references('id')->on('fieldsets')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('fields', function (Blueprint $table) {
            $table->dropForeign(['fieldset_id']);
        });
        Schema::dropIfExists('fields');
    }
}
