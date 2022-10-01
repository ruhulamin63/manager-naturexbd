<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBkashRefundLog extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bkash_refunds', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('original_trxID');
            $table->string('refund_trxID');
            $table->string('amount');
            $table->string('charge');
            $table->string('status');
            $table->string('timestamp');
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
        Schema::dropIfExists('bkash_refunds');
    }
}
