<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVacancyApplicationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vacancy_applications', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('vacancy_id');
            $table->string('title');
            $table->string('first_name');
            $table->string('last_name');
            $table->text('address');
            $table->string('nationality');
            $table->boolean('require_permission_to_work_in_uk');
            $table->unsignedInteger('number_of_dependents');
            $table->text('telephone_number');
            $table->text('mobile_number')->nullable();
            $table->boolean('have_own_transport');

            $table->string('currently_employed_by')->nullable();
            $table->string('current_position')->nullable();
            $table->text('seeking_employment_change_reason')->nullable();
            $table->unsignedInteger('weeks_notice_required')->nullable();
            $table->text('conviction_details')->nullable();
            $table->string('marquis_employee_reference_name')->nullable();
            $table->text('booked_holiday_details')->nullable();

            $table->boolean('have_any_disabilities');
            $table->text('disability_details')->nullable();
            $table->boolean('wear_glasses_or_contacts');
            $table->text('glasses_or_contacts_details')->nullable();
            $table->boolean('receiving_medical_treatment');
            $table->text('medical_treatment_details')->nullable();
            $table->boolean('smoker');

            $table->text('courses_and_certificates')->nullable();
            $table->text('practical_experience')->nullable();
            $table->text('hobbies_and_interests')->nullable();

            $table->text('references')->nullable();

            $table->timestamps();

            $table->foreign('vacancy_id')->references('id')->on('vacancies');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('vacancy_applications', function (Blueprint $table) {
            $table->dropForeign(['vacancy_id']);
        });

        Schema::dropIfExists('vacancy_applications');
    }
}
