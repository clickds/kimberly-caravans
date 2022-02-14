<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCaravanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('caravan', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->unsignedBigInteger('branch_id')->nullable(true);
            $table->unsignedBigInteger('category_id')->nullable(true);
            $table->unsignedBigInteger('type_id')->nullable(true);

            $table->integer('stock')->default(0);
            $table->string('reg',15)->default('unknown');
            $table->string('make',20)->default('unknown');
            $table->string('model',20)->default('unknown');
            $table->string('specification',255)->default('unknown');
            $table->string('derivative',255)->default('unknown');
            $table->string('engine_size',100)->default('unknown');
            $table->string('engine_type',100)->default('unknown');
            $table->string('transmission',100)->default('unknown');
            $table->string('colour',20)->default('unknown');
            $table->smallInteger('year')->default(0);
            $table->mediumInteger('mileage')->default(0);
            $table->boolean('commercial')->default(false);
            $table->decimal('sales_siv',10)->default(0);
            $table->decimal('retail',10)->default(0);
            $table->decimal('web_price',10)->default(0);
            $table->string('sub_heading',255)->nullable(true);
            $table->text('advertising_notes')->nullable(true);
            $table->text('manager_comments')->nullable(true);
            $table->double('previous_price',10)->default(0);
            $table->double('guide_retail_price',10)->default(0);
            $table->boolean('available_for_sale')->default(false);
            $table->boolean('advertised_on_own_website')->default(false);
            $table->tinyInteger('berths')->default(0);
            $table->tinyInteger('axles')->default(0);
            $table->string('layout_type')->default('unknown');
            $table->double('width')->default(0);
            $table->double('length')->default(0);
            $table->double('height')->default(0);
            $table->integer('kimberley_unit_id')->nullable(true);
            $table->dateTime('kimberley_date_updated')->useCurrent();


        });

        Schema::table('caravan',function(Blueprint $table) {

            // add foreign keys
            $table->foreign('branch_id')->references('id')->on('branch');
            $table->foreign('category_id')->references('id')->on('category');
            $table->foreign('type_id')->references('id')->on('type');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

        Schema::dropIfExists('caravan');
    }
}
