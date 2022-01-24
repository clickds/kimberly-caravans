<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AllowFieldsOnMotorhomesToBeNull extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('motorhomes', function (Blueprint $table) {
            $table->unsignedDecimal('width', 8, 2)->nullable()->change();
            $table->unsignedDecimal('height', 8, 2)->nullable()->change();
            $table->unsignedDecimal('length', 8, 2)->nullable()->change();
            $table->unsignedInteger('mro')->nullable()->change();
            $table->unsignedInteger('mtplm')->nullable()->change();
            $table->unsignedInteger('payload')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('motorhomes', function (Blueprint $table) {
            $table->unsignedDecimal('width', 8, 2)->nullable(false)->change();
            $table->unsignedDecimal('height', 8, 2)->nullable(false)->change();
            $table->unsignedDecimal('length', 8, 2)->nullable(false)->change();
            $table->unsignedInteger('mro')->nullable()->change(false);
            $table->unsignedInteger('mtplm')->nullable()->change(false);
            $table->unsignedInteger('payload')->nullable()->change(false);
        });
    }
}
