<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddEventStatusToForecastSummary extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('forecast_summary', function (Blueprint $table) {
            $table->integer('event_count')->unsigned()->after('forecast_id');
            $table->integer('prog_status')->default(0)->after('event_count');
            $table->string('result')->default("O")->after('prog_status');
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
