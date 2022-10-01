<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class KtMangoOrders extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kt_mango_orders', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->text('order_id');
            $table->text('type');
            $table->text('name');
            $table->text('mobile');
            $table->text('thana');
            $table->text('zilla');
            $table->text('courier');
            $table->text('quantity');
            $table->text('unit');
            $table->text('delivery_note');
            $table->text('trade_price');
            $table->text('sell_price');
            $table->text('profit');
            $table->text('tracking');
            $table->text('payment_method');
            $table->text('trx_id');
            $table->text('payment_status');
            $table->text('order_status');
            $table->text('timeline');
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
        Schema::dropIfExists('kt_mango_orders');
    }
}
