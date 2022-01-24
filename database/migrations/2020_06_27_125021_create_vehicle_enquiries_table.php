<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVehicleEnquiriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vehicle_enquiries', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('first_name');
            $table->string('surname');
            $table->string('email');
            $table->string('phone_number');
            $table->string('county');
            $table->text('message');
            $table->text('help_methods')->nullable();
            $table->text('interests')->nullable();
            $table->text('marketing_preferences')->nullable();
            $table->unsignedBigInteger('dealer_id')->index();
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
        Schema::table('vehicle_enquiries', function (Blueprint $table) {
            $table->dropForeign(['dealer_id']);
        });

        Schema::dropIfExists('vehicle_enquiries');
    }
}
