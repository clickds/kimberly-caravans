<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyDealerLocationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('dealer_locations', function (Blueprint $table) {
            $table->dropColumn('name');
            $table->dropColumn('service');
            $table->dropColumn('sales');
            $table->string('line_1')->nullable()->change();
            $table->string('town')->nullable()->change();
            $table->string('county')->nullable()->change();
            $table->string('postcode')->nullable()->change();
            $table->text('opening_hours')->after('name')->nullable();
            $table->text('sat_nav_code')->after('fax')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('dealer_locations', function (Blueprint $table) {
            $table->string('name');
            $table->boolean('service')->default(false)->index();
            $table->boolean('sales')->default(false)->index();
            $table->string('line_1')->change();
            $table->string('town')->change();
            $table->string('county')->change();
            $table->string('postcode')->change();
            $table->dropColumn('opening_hours');
            $table->dropColumn('sat_nav_code');
        });
    }
}
