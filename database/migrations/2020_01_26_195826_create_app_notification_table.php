<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAppNotificationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kt_app_notification', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->text('notification_id');
            $table->text('title');
            $table->text('message');
            $table->text('image');
            $table->text('redirect');
            $table->text('success');
            $table->text('failed');
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
        Schema::dropIfExists('kt_app_notification');
    }
}
