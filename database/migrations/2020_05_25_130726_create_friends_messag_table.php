<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFriendsMessagTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('friends_messag', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->unsignedBigInteger('friend_id');
            $table->foreign('friend_id')->references('id')->on('friends');

            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');

            $table->text('message'); //訊息

            $table->boolean('type')->default('0'); //訊息是否讀取

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
        Schema::dropIfExists('friends_messag');
    }
}
