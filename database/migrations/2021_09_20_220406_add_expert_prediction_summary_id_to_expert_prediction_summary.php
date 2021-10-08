<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddExpertPredictionSummaryIdToExpertPredictionSummary extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('bookmakers_codes', function (Blueprint $table) {
            $table->integer('expert_prediction_summary_id')->after('id')->unsigned();

            $table->foreign('expert_prediction_summary_id')->references('id')->on('forecast_summary')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('bookmakers_codes', function (Blueprint $table) {
            //
        });
    }
}
