<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRestaurantPropertyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kt_restaurant_property', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->text('restaurant_id');
            $table->text('restaurant_rating');
            $table->text('opening_time');
            $table->text('closing_time');
            $table->text('restaurant_area');
            $table->text('discount_percentage');
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
        Schema::dropIfExists('kt_restaurant_property');
    }
}
