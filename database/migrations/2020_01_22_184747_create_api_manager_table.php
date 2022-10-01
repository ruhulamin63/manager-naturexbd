<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateApiManagerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kt_api_manager', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->text('service_name');
            $table->text('service_identifier');
            $table->text('used_balance');
            $table->text('api_key');
            $table->integer('api_usage')->default('0');
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
        Schema::dropIfExists('kt_api_manager');
    }
}
