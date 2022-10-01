<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKtRiderTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kt_riders', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->text('rider_id');
            $table->text('city_id');
            $table->text('name');
            $table->text('mobile');
            $table->text('password');
            $table->text('device_token');
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
        Schema::dropIfExists('kt_riders');
    }
}
