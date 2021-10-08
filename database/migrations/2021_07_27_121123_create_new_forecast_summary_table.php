<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNewForecastSummaryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('forecast_summary', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('expert_id')->unsigned();
            $table->string('forecast_id');
            $table->timestamps();

            $table->foreign('expert_id')->references('id')->on('experts')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('forecast_summary');
    }
}
