<?php

use App\Models\Fieldset;
use App\Models\Form;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFieldsetFormTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fieldset_form', function (Blueprint $table) {
            $table->unsignedBigInteger('fieldset_id');
            $table->unsignedBigInteger('form_id');
            $table->integer('position')->default(0)->index();

            $table->unique(['form_id', 'fieldset_id']);
            $table->foreign('fieldset_id')->references('id')->on('fieldsets')
                ->onDelete('cascade');
            $table->foreign('form_id')->references('id')->on('forms')
                ->onDelete('cascade');
        });
        Form::chunk(200, function ($forms) {
            foreach ($forms as $form) {
                $fieldsetIds = Fieldset::where('form_id', $form->id)->pluck('id');
                $form->fieldsets()->attach($fieldsetIds);
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('fieldset_form', function (Blueprint $table) {
            $table->dropForeign(['form_id']);
            $table->dropForeign(['fieldset_id']);
        });
        Schema::dropIfExists('fieldset_form');
    }
}
