<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPaymentResToPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->string('message')->nullable()->after('status');
            $table->string('ref_id')->nullable()->after('message');
            $table->string('trans')->nullable()->after('ref_id');
            $table->string('trx_ref')->nullable()->after('trans');
            $table->string('mode')->nullable()->after('trx_ref');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('payments', function (Blueprint $table) {
            //
        });
    }
}
