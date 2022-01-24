<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePanelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('panels', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('area_id');
            $table->unsignedInteger('position')->default(0);
            $table->string('name');
            $table->string('heading')->nullable();
            $table->string('type');
            $table->string('vertical_positioning');
            $table->string('overlay_position')->nullable();
            $table->text('content')->nullable();
            $table->text('read_more_content')->nullable();
            $table->text('vehicle_type')->nullable();
            $table->unsignedBigInteger('featureable_id')->nullable();
            $table->string('featureable_type')->nullable();
            $table->timestamp('published_at')->nullable(true);
            $table->timestamp('expired_at')->nullable(true);
            $table->boolean('live')->default(true);
            $table->timestamps();

            $table->index(['published_at', 'expired_at', 'live']);
            $table->index(['featureable_id', 'featureable_type'], 'featurable_index');
            $table->foreign('area_id')->references('id')->on('areas')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('panels', function (Blueprint $table) {
            $table->dropForeign(['area_id']);
        });
        Schema::dropIfExists('panels');
    }
}
