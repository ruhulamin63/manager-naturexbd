<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSmsHistoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kt_sms_history', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->text('campaign');
            $table->text('sendTo');
            $table->text('message');
            $table->text('totalSMS');
            $table->text('totalCost');
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
        Schema::dropIfExists('kt_sms_history');
    }
}
