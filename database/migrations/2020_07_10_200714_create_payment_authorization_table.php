<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentAuthorizationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment_authorization', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('order_id');
            $table->string('token');
            $table->string('init_time');
            $table->string('expired_on')->default("N/A");
            $table->string('payment_channel')->default("N/A");
            $table->string('timeline');
            $table->string('current_status')->default("Pending");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payment_authorization');
    }
}
