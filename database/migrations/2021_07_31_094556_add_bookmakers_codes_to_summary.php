<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddBookmakersCodesToSummary extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('forecast_summary', function (Blueprint $table) {
            $table->string('bet9ja')->nullable();
            $table->string('betking')->nullable();
            $table->string('merrybet')->nullable();
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
