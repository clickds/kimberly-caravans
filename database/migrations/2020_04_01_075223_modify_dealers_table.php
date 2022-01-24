<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyDealersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('dealers', function (Blueprint $table) {
            $table->dropColumn('opening_hours');
            $table->string('video_embed_code')->after('name')->nullable();
            $table->text('facilities')->after('video_embed_code')->nullable();
            $table->text('servicing_centre')->after('facilities')->nullable();
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
            $table->text('opening_hours');
            $table->dropColumn('video_embed_code');
            $table->dropColumn('facilities');
            $table->dropColumn('servicing_centre');
        });
    }
}
