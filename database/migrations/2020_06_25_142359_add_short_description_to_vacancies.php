<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddShortDescriptionToVacancies extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Changing a timestamp type column doesn't work due to an issue with Doctrine.
        // https://github.com/doctrine/dbal/issues/2558
        // So need to drop the column and add it again in the desired format.
        Schema::table('vacancies', function (Blueprint $table) {
            $table->longText('short_description')->after('salary');
            $table->dropColumn('published_at');
        });

        Schema::table('vacancies', function (Blueprint $table) {
            $table->timestamp('published_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('vacancies', function (Blueprint $table) {
            $table->dropColumn('short_description');
            $table->dropColumn('published_at');
        });

        Schema::table('vacancies', function (Blueprint $table) {
            $table->timestamp('published_at')->nullable(true);
        });

    }
}
