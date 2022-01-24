<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPrependRangeNameToMotorhomeRangesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('motorhome_ranges', function (Blueprint $table) {
            $table->boolean('prepend_range_name_to_model_names')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('motorhome_ranges', function (Blueprint $table) {
            $table->dropColumn('prepend_range_name_to_model_names');
        });
    }
}
