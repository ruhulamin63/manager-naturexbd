<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAppUserPreferenceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kt_app_user_preference', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->text('user_id');
            $table->text('favorite_category');
            $table->text('favorite_item');
            $table->text('favorite_restaurant');
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
        Schema::dropIfExists('kt_app_user_preference');
    }
}
