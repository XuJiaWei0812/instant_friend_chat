<?php

namespace App\Events;

use DB;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class getRecordMessage implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $record;
    public $rosterUrl;
    public function __construct($user_id)
    {
        $friendRecords = DB::table('friends')
            ->join('users', function ($join) {
                $join->on('users.id', '=', 'friends.inviter_user_id')
                    ->orOn('users.id', '=', 'friends.invitee_user_id');
            })
            ->join(DB::raw('(select * from friends_message,
            (select max(created_at) as maxtime,
            count(IF(type = 0 AND user_id = '.$user_id.',true,null)) as unread
            from friends_message group by friend_id) c where friends_message.created_at=c.maxtime) as friends_message'), function ($join) {
                $join->on('friends_message.friend_id', '=', 'friends.id');
            })
            ->select(
                'friends.id as fid',
                'users.id as uid',
                'name',
                'photo',
                'message',
                'unread',
                DB::raw("DATE_FORMAT(maxtime,'%H:%i') as time"),
            )
            ->where('users.id','<>', $user_id)
            ->Where(function ($query) use ($user_id) {
                $query->where('inviter_user_id', $user_id)
                    ->orWhere('invitee_user_id', $user_id);
            })
            ->orderBy('maxtime', 'desc')
            ->get();

        foreach ($friendRecords as &$friendRecord) {
            if (strpos($friendRecord->message, 'images') !== false) {
                $friendRecord->message = "對方發送圖片訊息";
            } else {
                $friendRecord->message=mb_substr(preg_replace('/<[^>]+>|&[^>]+;/', '', $friendRecord->message), 0, 12, 'utf8');
            }
            $friendRecord->photo=asset($friendRecord->photo);
        }
        unset($friendRecord);

        $this->record=$friendRecords;
        $this->rosterUrl=asset('/friend/chat/');
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('get-recordMessage');
    }
}
