<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Friend extends Model
{
    protected $table='friends';

    protected $primaryKey = 'id';

    protected $fillable = [
        'id','inviter_user_id', 'invitee_user_id', 'type',
    ];
}
