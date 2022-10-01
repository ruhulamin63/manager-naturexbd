<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMegaDaysProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mega_days_products', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('cid');
            $table->string('pid');
            $table->string('category_name');
            $table->text('product_name');
            $table->text('product_description');
            $table->string('regular_price');
            $table->string('discounted_price');
            $table->text('product_image');
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
        Schema::dropIfExists('mega_days_products');
    }
}
