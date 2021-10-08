<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDailyTipsMailingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('daily_tips_mailings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('daily_tips_summary_id')->unsigned();
            $table->timestamps();

            $table->foreign('daily_tips_summary_id')->references('id')->on('daily_tips_summary')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('daily_tips_mailings');
    }
}
