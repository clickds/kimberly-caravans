<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeArticlesToHaveManyArticleCategories extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('articles', function (Blueprint $table) {
            $table->dropForeign(['article_category_id']);
            $table->dropColumn('article_category_id');
        });

        Schema::create('article_article_category', function (Blueprint $table) {
            $table->unsignedBigInteger('article_id');
            $table->unsignedBigInteger('article_category_id');

            $table->unique(['article_id', 'article_category_id']);
            $table->foreign('article_id')->references('id')->on('articles')->onDelete('cascade');
            $table->foreign('article_category_id')->references('id')->on('article_categories')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('article_article_category', function (Blueprint $table) {
            $table->dropForeign(['article_id']);
            $table->dropForeign(['article_category_id']);
        });
        Schema::dropIfExists('article_article_category');
        Schema::table('articles', function (Blueprint $table) {
            $table->unsignedBigInteger('article_category_id')->nullable();
            $table->foreign('article_category_id')->references('id')->on('article_categories')->onDelete('cascade');
        });
    }
}
