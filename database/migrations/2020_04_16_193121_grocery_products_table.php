<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class GroceryProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('grocery_products', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->text('cityID');
            $table->text('category');
            $table->text('product_name');
            $table->text('product_price');
            $table->text('measuring_unit');
            $table->text('product_description');
            $table->text('product_thumbnail');
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
        Schema::dropIfExists('grocery_products');
    }
}
