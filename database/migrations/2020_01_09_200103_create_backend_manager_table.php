<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBackendManagerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kt_backend_manager_info', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->text('uid');
            $table->text('name');
            $table->text('email');
            $table->text('mobile');
            $table->text('photo');
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
        Schema::dropIfExists('kt_backend_manager_info');
    }
}
