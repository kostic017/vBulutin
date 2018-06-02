<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class PostVersionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('post_versions', function (Blueprint $table) {
            $table->increments('id');
            $table->string('original_content');
            $table->string('edit_reason')->nullable();
            $table->timestamp('edited_at');
            $table->integer('post_id');
            $table->integer('user_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('post_versions');
    }
}
