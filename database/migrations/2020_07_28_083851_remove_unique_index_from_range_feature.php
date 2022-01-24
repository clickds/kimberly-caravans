<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveUniqueIndexFromRangeFeature extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('range_features', function (Blueprint $table) {
            $table->dropUnique(['vehicle_range_type', 'vehicle_range_id', 'name']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('range_features', function (Blueprint $table) {
            $table->unique(['vehicle_range_type', 'vehicle_range_id', 'name']);
        });
    }
}
