<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVacancyDealersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vacancy_dealers', function (Blueprint $table) {
            $table->unsignedBigInteger('vacancy_id');
            $table->unsignedBigInteger('dealer_id');
            $table->timestamps();

            $table->foreign('vacancy_id')->references('id')->on('vacancies');
            $table->foreign('dealer_id')->references('id')->on('dealers');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vacancy_dealers');
    }
}
