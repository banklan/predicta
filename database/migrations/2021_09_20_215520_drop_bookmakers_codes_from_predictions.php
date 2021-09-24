<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DropBookmakersCodesFromPredictions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('forecast_summary', function (Blueprint $table) {
            $table->dropColumn('bet9ja');
            $table->dropColumn('betking');
            $table->dropColumn('merrybet');
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
