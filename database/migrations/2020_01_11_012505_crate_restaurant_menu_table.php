<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CrateRestaurantMenuTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kt_restaurants_menu', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->text('restaurant_id');
            $table->text('item_id');
            $table->text('item_name');
            $table->text('item_description')->nullable();
            $table->text('price');
            $table->text('discount');
            $table->text('discount_type');
            $table->text('discount_amount');
            $table->text('status');
            $table->text('updated_by');
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
        Schema::dropIfExists('kt_restaurant_menu');
    }
}
