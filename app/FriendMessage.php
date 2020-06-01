<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FriendMessage extends Model
{
   protected $table='friends_message';

    protected $primaryKey = 'id';

    protected $fillable = [
        'id','friend_id', 'user_id', 'message','type'
    ];
}
