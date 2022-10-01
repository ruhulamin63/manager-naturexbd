<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class GroceryOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('grocery_orders', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->text('order_id');
            $table->text('city_id');
            $table->text('city_name');
            $table->text('customer_name');
            $table->text('delivery_address');
            $table->text('contact_number');
            $table->text('delivery_note');
            $table->text('order_data');
            $table->text('product_total')->nullable();
            $table->text('delivery_charge')->nullable();
            $table->text('discount')->nullable();
            $table->text('total_amount');
            $table->text('order_status');
            $table->text('order_remarks');
            $table->text('scheduled_date');
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
        Schema::dropIfExists('grocery_orders_raw');
    }
}
