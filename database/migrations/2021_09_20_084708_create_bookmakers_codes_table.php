<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBookmakersCodesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bookmakers_codes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('forecast_summary_id')->unsigned();
            $table->integer('expert_id');
            $table->integer('bookmaker_id');
            $table->string('bookmaker_code');
            $table->timestamps();

            $table->foreign('forecast_summary_id')->references('id')->on('forecast_summary');
            $table->foreign('expert_id')->references('id')->on('experts');
            $table->foreign('bookmaker_id')->references('id')->on('bookmakers');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bookmakers_codes');
    }
}
