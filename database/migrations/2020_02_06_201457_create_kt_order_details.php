<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKtOrderDetails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kt_order_details', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->text('user_id');
            $table->text('order_id');
            $table->text('item_details');
            $table->text('delivery_fee');
            $table->text('multi_res_fee');
            $table->text('total_bill');
            $table->text('type');
            $table->text('payment');
            $table->text('receiver_name');
            $table->text('to_addr');
            $table->text('city');
            $table->text('contact');
            $table->text('orderNote');
            $table->text('status');
            $table->text('rider_id');
            $table->text('order_date');
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
        Schema::dropIfExists('kt_order_details');
    }
}
