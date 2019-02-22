<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLikesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('likes', function (Blueprint $table) {
            $table->increments('id');

            $table->unsignedInteger('user_id');
            $table->unsignedInteger('posts_id');
            $table->unsignedInteger('comments_id');
            
            $table->index(['user_id','posts_id']);
            $table->index(['user_id','comments_id']);

            $table->foreign('posts_id')
                  ->references('id')
                  ->on('posts')
                  ->onDelete('cascade');
            $table->foreign('comments_id')
                  ->references('id')
                  ->on('comments')
                  ->onDelete('cascade');

            $table->boolean('dislike')->default(0);

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
        Schema::dropIfExists('likes');
    }
}
