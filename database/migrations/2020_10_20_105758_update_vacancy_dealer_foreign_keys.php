<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateVacancyDealerForeignKeys extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('vacancy_dealers', function (Blueprint $table) {
            $table->dropForeign(['vacancy_id']);
            $table->dropForeign(['dealer_id']);
            $table->foreign('vacancy_id')->references('id')->on('vacancies')->cascadeOnDelete();
            $table->foreign('dealer_id')->references('id')->on('dealers')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('vacancy_dealers', function (Blueprint $table) {
            $table->dropForeign(['vacancy_id']);
            $table->dropForeign(['dealer_id']);
            $table->foreign('vacancy_id')->references('id')->on('vacancies');
            $table->foreign('dealer_id')->references('id')->on('dealers');
        });
    }
}
