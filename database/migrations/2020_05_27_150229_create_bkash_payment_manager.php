<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBkashPaymentManager extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bkash_history', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->text('order_id');
            $table->text('invoice_number');
            $table->text('grant_token');
            $table->text('refresh_token');
            $table->text('token_time');
            $table->text('payment_id');
            $table->text('amount');
            $table->text('history');
            $table->text('trxID');
            $table->text('transactionStatus');
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
        Schema::dropIfExists('kt_bkash_history');
    }
}
