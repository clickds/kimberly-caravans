<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmailRecipientFormTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('email_recipient_form', function (Blueprint $table) {
            $table->unsignedBigInteger('email_recipient_id');
            $table->unsignedBigInteger('form_id');

            $table->unique(['form_id', 'email_recipient_id']);

            $table->foreign('form_id')->references('id')->on('forms')->onDelete('cascade');
            $table->foreign('email_recipient_id')->references('id')->on('email_recipients')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('email_recipient_form', function (Blueprint $table) {
            $table->dropForeign(['email_recipient_id']);
            $table->dropForeign(['form_id']);
        });
        Schema::dropIfExists('email_recipient_form');
    }
}
