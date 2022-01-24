<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVacancyApplicationEmploymentHistoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vacancy_application_employment_history', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('vacancy_application_id');
            $table->string('position');
            $table->dateTime('start_date');
            $table->dateTime('end_date')->nullable();
            $table->text('employer_details');
            $table->text('reasons_for_leaving');
            $table->timestamps();

            $table->foreign('vacancy_application_id', 'vaeh_vacancy_application_id_foreign')->references('id')->on('vacancy_applications');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('vacancy_application_employment_history', function (Blueprint $table) {
            $table->dropForeign('vaeh_vacancy_application_id_foreign');
        });

        Schema::dropIfExists('vacancy_application_employment_history');
    }
}
