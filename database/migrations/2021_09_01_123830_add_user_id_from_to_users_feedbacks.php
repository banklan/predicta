<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddUserIdFromToUsersFeedbacks extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users_feedbacks', function (Blueprint $table) {
            $table->dropColumn('user_id');
            $table->integer('user_id_from')->unsigned()->after('id');
            $table->integer('user_id_to')->unsigned()->after('id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('usersfeedbacks', function (Blueprint $table) {
            //
        });
    }
}
