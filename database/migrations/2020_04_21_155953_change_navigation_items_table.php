<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeNavigationItemsTable extends Migration
{
    public function up()
    {
        Schema::table('navigation_items', function (Blueprint $table) {
            $table->string('url')->nullable()->change();
            $table->dropColumn('internal_id');
            $table->integer('display_order')->after('parent_id')->default(0);

            $table->foreign('parent_id')->references('id')->on('navigation_items');
        });

        Schema::table('navigation_items', function (Blueprint $table) {
            $table->renameColumn('page', 'page_id');
            $table->renameColumn('url', 'external_url');

            $table->foreign('page_id')->references('id')->on('pages');
        });
    }

    public function down()
    {
        Schema::table('navigation_items', function (Blueprint $table) {
            $table->dropForeign(['page_id']);
            $table->dropColumn('display_order');
            $table->renameColumn('page_id', 'page');
            $table->renameColumn('external_url', 'url');
        });

        Schema::table('navigation_items', function (Blueprint $table) {
            $table->string('url')->change();
            $table->unsignedBigInteger('internal_id')->after('page');

            $table->dropForeign(['parent_id']);
        });
    }
}
