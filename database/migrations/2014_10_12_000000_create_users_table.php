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

            // Credentials

            $table->string("username");
            $table->string('password');

            $table->string('email');
            $table->string("email_token")->nullable();
            $table->boolean("is_confirmed")->default(false);

            // Profile

            $table->text("about")->nullable();
            $table->date("birthday_on")->nullable();
            $table->enum("sex", ["m", "f", "s"])->nullable();

            $table->string("job")->nullable();
            $table->string('name')->nullable();
            $table->string("residence")->nullable();
            $table->string("birthplace")->nullable();
            $table->string("avatar")->nullable();

            // Technical Info

            $table->boolean("is_admin")->default(false);
            $table->boolean("is_logged_in")->default(false);
            $table->boolean("is_invisible")->default(false);

            $table->timestamp("registered_at")->useCurrent();
            $table->timestamp("last_login_at")->nullable();
            $table->timestamp("last_activity_at")->nullable();

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
