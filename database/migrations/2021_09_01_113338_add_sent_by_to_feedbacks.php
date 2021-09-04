<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSentByToFeedbacks extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users_feedbacks', function (Blueprint $table) {
            $table->boolean('is_parent')->default(false)->after('user_id');
            $table->integer('parent_id')->nullable()->after('is_parent');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('usrs_feedbacks', function (Blueprint $table) {
            //
        });
    }
}
