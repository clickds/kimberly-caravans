<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAdditionalFieldsToMotorhomes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('motorhomes', function (Blueprint $table) {
            $table->unsignedDecimal('high_line_height', 8, 2)->nullable();
            $table->unsignedInteger('maximum_trailer_weight')->nullable();
            $table->unsignedInteger('gross_train_weight')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('motorhomes', function (Blueprint $table) {
            $table->dropColumn('high_line_height');
            $table->dropColumn('maximum_trailer_weight');
            $table->dropColumn('gross_train_weight');
        });
    }
}
