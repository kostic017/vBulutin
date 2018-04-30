<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ProfilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('profiles', function (Blueprint $table) {
            $table->integer('user_id');
            $table->text('about')->nullable();
            $table->date('birthday_on')->nullable();
            $table->enum('sex', ['m', 'f', 's'])->nullable();
            $table->string('job')->nullable();
            $table->string('name')->nullable();
            $table->string('residence')->nullable();
            $table->string('birthplace')->nullable();
            $table->string('avatar')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('profiles');
    }
}
