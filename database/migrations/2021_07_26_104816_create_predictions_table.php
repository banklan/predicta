<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePredictionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('predictions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('expert_id')->unsigned();
            $table->string('prediction_code');
            $table->string('country')->nullable();
            $table->string('league');
            $table->string('home');
            $table->string('away');
            $table->string('tip');
            $table->string('odd');
            $table->date('event_date');
            $table->time('event_time');
            $table->boolean('is_open')->default(true);
            $table->boolean('is_won')->default(false);
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
        Schema::dropIfExists('predictions');
    }
}
