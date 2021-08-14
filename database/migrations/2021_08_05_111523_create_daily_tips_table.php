<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDailyTipsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('daily_tips', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('admin_id')->unsigned();
            $table->string('tip_code');
            $table->string('country')->nullable();
            $table->string('league')->nullable();
            $table->string('home');
            $table->string('away');
            $table->string('tip');
            $table->string('odd')->nullable();
            $table->date('event_date');
            $table->time('event_time');
            $table->boolean('is_featured')->default(0);
            $table->integer('status')->default(0)->unsigned();
            $table->timestamps();

            $table->foreign('admin_id')->references('id')->on('admins');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('daily_tips');
    }
}
