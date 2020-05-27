<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFriendsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('friends', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->unsignedBigInteger('inviter_user_id');
            $table->foreign('inviter_user_id')->references('id')->on('users');

            $table->unsignedBigInteger('invitee_user_id');
            $table->foreign('invitee_user_id')->references('id')->on('users');

            $table->boolean('type')->default('0'); //好友狀態

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
        Schema::dropIfExists('friends');
    }
}
