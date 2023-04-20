<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('upvote_downvotes', function (Blueprint $table) {
            $table->id();
            $table->boolean('is_upvoted');
            $table->foreignId('id_post')->references('id')->on('posts')->onDelete('cascade');
            $table->foreignId('id_user')->references('id')->on('users')->onDelete('cascade');
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
        Schema::dropIfExists('upvote_downvotes');
    }
};
