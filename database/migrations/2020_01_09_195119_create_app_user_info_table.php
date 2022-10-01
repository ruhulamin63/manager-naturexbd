<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAppUserInfoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kt_app_user_info', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->text('user_id');
            $table->text('user_phone');
            $table->text('user_name');
            $table->text('user_email');
            $table->text('user_address');
            $table->text('user_coordinate');
            $table->text('user_gender');
            $table->text('blood_group');
            $table->text('date_of_birth');
            $table->text('user_photo');
            $table->text('user_referral');
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
        Schema::dropIfExists('kt_app_user_info');
    }
}
