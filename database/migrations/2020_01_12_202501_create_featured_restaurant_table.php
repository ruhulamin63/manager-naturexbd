<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFeaturedRestaurantTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kt_restaurants_featured', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->text('city_id');
            $table->text('category');
            $table->text('sequence');
            $table->text('featured_restaurants');
            $table->text('status');
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
        Schema::dropIfExists('kt_restaurants_featured');
    }
}
