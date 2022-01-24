<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAdditionalFieldsToPopUps extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pop_ups', function (Blueprint $table) {
            $table->boolean('appears_on_all_pages')->default(false);
            $table->boolean('appears_on_new_motorhome_pages')->default(false);
            $table->boolean('appears_on_new_caravan_pages')->default(false);
            $table->boolean('appears_on_used_motorhome_pages')->default(false);
            $table->boolean('appears_on_used_caravan_pages')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pop_ups', function (Blueprint $table) {
            $table->dropColumn('appears_on_all_pages');
            $table->dropColumn('appears_on_new_motorhome_pages');
            $table->dropColumn('appears_on_new_caravan_pages');
            $table->dropColumn('appears_on_used_motorhome_pages');
            $table->dropColumn('appears_on_used_caravan_pages');
        });
    }
}
