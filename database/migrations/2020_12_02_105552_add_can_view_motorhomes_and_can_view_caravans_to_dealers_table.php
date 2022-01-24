<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCanViewMotorhomesAndCanViewCaravansToDealersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('dealers', function (Blueprint $table) {
            $table->boolean('can_view_motorhomes')->default(true)->after('is_servicing_center');
            $table->boolean('can_view_caravans')->default(true)->after('can_view_motorhomes');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('dealers', function (Blueprint $table) {
            $table->dropColumn('can_view_motorhomes');
            $table->dropColumn('can_view_caravans');
        });
    }
}
