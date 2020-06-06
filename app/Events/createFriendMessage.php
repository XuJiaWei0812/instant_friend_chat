<?php

namespace App\Events;

use App\FriendMessage;
use DB;
use Image;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class createFriendMessage implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $message;

    public function __construct($fid, $uid, $input)
    {
        if (isset($input['file'])) {
            $photo = $input['file']; //有上傳圖片
            $file_extension = $photo->getClientOriginalExtension(); //取得副檔名
            $file_name = uniqid() . '.' . $file_extension;
            $file_relative_path = 'images/' . $file_name;
            $file_path = public_path($file_relative_path);
            $image = Image::make($photo)->fit(390, 300)->save($file_path);
            $input['message'] = $file_relative_path;
        }
        $input['friend_id']=$fid;
        $input['user_id']=$uid;
        $addMessage = FriendMessage::create($input);
        $getMessage = DB::table('friends_message')
            ->join('users', function ($join) {
                $join->on('users.id', '=', 'friends_message.user_id');
            })
            ->select(
                'friends_message.id',
                'friends_message.friend_id',
                'users.id as uid',
                'name',
                'photo',
                'message',
                DB::raw("DATE_FORMAT(friends_message.created_at,'%H:%i') as time"),
            )
            ->where('friends_message.id', $addMessage->id)
            ->where('friend_id', $fid)
            ->orderBy('time', 'desc')
            ->first();

        $getMessage->photo = asset($getMessage->photo);
        if (strpos($getMessage->message, 'images') !== false) {
            $getMessage->message = asset($getMessage->message);
        }
        $this->message =  $getMessage;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('create-friendMessage');
    }
}
