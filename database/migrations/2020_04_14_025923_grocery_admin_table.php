<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class GroceryAdminTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('grocery_admin', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->text('user_id');
            $table->text('name');
            $table->text('mobile');
            $table->text('email');
            $table->text('password');
            $table->text('permissions');
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
        Schema::dropIfExists('grocery_admin');
    }
}
