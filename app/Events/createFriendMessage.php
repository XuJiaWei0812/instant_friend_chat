<?php

namespace App\Events;

use App\FriendMessage;
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
            $file_relative_path = 'images/friendImage/' . $file_name;
            $file_path = public_path($file_relative_path);
            $image = Image::make($photo)->fit(390, 300)->save($file_path);
            $input['file'] = $file_relative_path;
            $input['message']= $input['file'];
        }
        $input['friend_id']=$fid;
        $input['user_id']=$uid;
        $friendMessage = FriendMessage::create($input);

        $this->message =  "ok";
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
