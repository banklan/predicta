<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DropForecastSummaryIdFromPredictions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('bookmakers_codes', function (Blueprint $table) {
            $table->dropColumn('forecast_summary_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('forecast_summary', function (Blueprint $table) {
            //
        });
    }
}
