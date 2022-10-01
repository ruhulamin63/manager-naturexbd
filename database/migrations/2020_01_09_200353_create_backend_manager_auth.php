<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBackendManagerAuth extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kt_backend_manager_auth', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->text('uid');
            $table->text('email');
            $table->text('password');
            $table->text('role_id');
            $table->text('last_login');
            $table->text('last_login_ip');
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
        Schema::dropIfExists('kt_backend_manager_auth');
    }
}
