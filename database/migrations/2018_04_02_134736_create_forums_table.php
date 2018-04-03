<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateForumsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('forums', function (Blueprint $table) {
            $table->increments('id');

            $table->string("slug");
            $table->string("title");
            $table->text("description")->nullable();

            $table->integer("position");
            $table->boolean("is_locked")->default(false);

            $table->integer("section_id");
            $table->integer("parent_id")->nullable();

            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('forums');
    }
}
