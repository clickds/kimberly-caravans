<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIsBranchAndIsServicingCenterToDealersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('dealers', function (Blueprint $table) {
            $table->boolean('is_branch')->default(true)->after('name');
            $table->boolean('is_servicing_center')->default(false)->after('is_branch');
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
            $table->dropColumn('is_branch');
            $table->dropColumn('is_servicing_center');
        });
    }
}
