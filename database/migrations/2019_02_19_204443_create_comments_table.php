<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('comments', function (Blueprint $table) {
            $table->increments('id');

            $table->unsignedInteger('user_id');
            $table->unsignedInteger('posts_id');
            $table->index(['user_id', 'posts_id']);
            $table->foreign('posts_id')
                  ->references('id')
                  ->on('posts')
                  ->onDelete('cascade');

            $table->text('content');
            $table->unsignedInteger('reply_to')->default(0);
            $table->index('reply_to');

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
        Schema::dropIfExists('comments');
    }
}
