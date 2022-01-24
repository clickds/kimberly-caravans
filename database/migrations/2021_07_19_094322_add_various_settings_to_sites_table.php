<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddVariousSettingsToSitesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sites', function (Blueprint $table) {
            $table->boolean('show_live_chat')->default(true);
            $table->boolean('show_social_icons')->default(true);
            $table->boolean('show_accreditation_icons')->default(true);
            $table->boolean('show_footer_content')->default(true);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sites', function (Blueprint $table) {
            $table->dropColumn('show_live_chat');
            $table->dropColumn('show_social_icons');
            $table->dropColumn('show_accreditation_icons');
            $table->dropColumn('show_footer_content');
        });
    }
}
