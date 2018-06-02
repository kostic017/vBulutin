<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('username');
            $table->string('password');
            $table->string('email');
            $table->string('email_token')->nullable();
            $table->boolean('is_admin')->default(false);
            $table->boolean('is_invisible')->default(false);
            $table->boolean('is_banned')->default(false);
            $table->boolean('to_logout')->default(false);
            $table->timestamp('registered_at')->useCurrent();
            $table->timestamp('last_login_at')->nullable();
            $table->rememberToken();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
