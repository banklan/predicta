<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmailConfirmationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('email_confirmations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('expert_id');
            $table->string('token');
            $table->boolean('is_confirmed')->default(false);
            $table->timestamps();

            $table->foreign('expert_id')->references('id')->on('experts');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('email_confirmations');
    }
}
