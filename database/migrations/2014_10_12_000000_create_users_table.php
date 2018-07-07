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
            $table->integer('admin_of')->nullable();
            $table->boolean('is_master')->default(false);
            $table->boolean('is_invisible')->default(false);
            $table->boolean('to_logout')->default(false);
            $table->timestamp('registered_at')->useCurrent();
            $table->rememberToken();

            $table->text('about')->nullable();
            $table->date('birthday_on')->nullable();
            $table->enum('sex', ['m', 'f', 'o'])->nullable();
            $table->string('job')->nullable();
            $table->string('name')->nullable();
            $table->string('residence')->nullable();
            $table->string('birthplace')->nullable();
            $table->string('avatar')->nullable();
            $table->text('signature')->nullable();
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
