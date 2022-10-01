<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAppUserDeviceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kt_app_user_device', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->text('user_id');
            $table->text('version_release');
            $table->text('version_sdk');
            $table->text('manufacturer');
            $table->text('model');
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
        Schema::dropIfExists('kt_app_user_device');
    }
}
