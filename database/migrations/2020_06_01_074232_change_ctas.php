<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeCtas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ctas', function (Blueprint $table) {
            $table->dropColumn('link');

            $table->unsignedBigInteger('site_id')->index();
            $table->unsignedBigInteger('page_id')->nullable()->index();
            $table->string('type');
            $table->string('link_text')->nullable();
            $table->string('excerpt_text')->nullable()->change();
            $table->integer('position')->default(0)->index();

            $table->foreign('page_id')->references('id')->on('pages')->onDelete('cascade');
            $table->foreign('site_id')->references('id')->on('sites')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ctas', function (Blueprint $table) {
            $table->dropForeign(['page_id']);
            $table->dropForeign(['site_id']);

            $table->dropColumn('page_id');
            $table->dropColumn('site_id');
            $table->dropColumn('type');
            $table->dropColumn('link_text');
            $table->dropColumn('position');
            $table->string('excerpt_text')->nullable(false)->change();

            $table->string('link');
        });
    }
}
