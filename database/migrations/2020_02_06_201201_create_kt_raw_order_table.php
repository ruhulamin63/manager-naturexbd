<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKtRawOrderTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kt_order_raw', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->text('order_id');
            $table->text('orders');
            $table->text('info');
            $table->text('status');
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
        Schema::dropIfExists('kt_order_raw');
    }
}
