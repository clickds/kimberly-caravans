<?php

use App\Models\Fieldset;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveFormIdFromFieldsets extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('fieldsets', function (Blueprint $table) {
            $table->dropForeign(['form_id']);
            $table->dropColumn('form_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('fieldsets', function (Blueprint $table) {
            $table->unsignedBigInteger('form_id')->nullable()->index();
            $table->foreign('form_id')->references('id')->on('forms')->onDelete('cascade');
        });
        Fieldset::chunk(200, function ($fieldsets) {
            foreach ($fieldsets as $fieldset) {
                $form = $fieldset->forms()->first();
                $fieldset->form_id = $form->id;
                $fieldset->save();
            }
        });
        Schema::table('fieldsets', function (Blueprint $table) {
            $table->unsignedBigInteger('form_id')->nullable(false)->change();
        });
    }
}
