<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRestaurantTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kt_restaurants_list', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->text('city_id');
            $table->text('restaurant_id');
            $table->text('restaurant_name');
            $table->text('restaurant_category');
            $table->text('restaurant_address');
            $table->text('restaurant_mobile');
            $table->text('restaurant_coordinate');
            $table->text('restaurant_logo');
            $table->text('restaurant_preview');
            $table->text('delivery_charge');
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
        Schema::dropIfExists('kt_restaurants');
    }
}
