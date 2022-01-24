<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDealerEmployeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dealer_employees', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('dealer_id')->index();
            $table->string('name');
            $table->string('role');
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->integer('position')->nullable();
            $table->timestamps();

            $table->foreign('dealer_id')->references('id')->on('dealers')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('dealer_employees', function (Blueprint $table) {
            $table->dropForeign(['dealer_id']);
        });

        Schema::dropIfExists('dealer_employees');
    }
}
