<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\FriendMessage;
use Illuminate\Support\Facades\Auth;
use Cache;
use Illuminate\Support\Facades\Artisan;

class friendPageController extends Controller
{
    public function getRosters()
    {
        $rosters=DB::table('friends')
            ->join('users', function ($join) {
                $join->on('users.id', '=', 'friends.inviter_user_id')
                    ->orOn('users.id', '=', 'friends.invitee_user_id');
            })
            ->select(
                'friends.id as friend_id',
                'users.id as friend_userId',
                'name as friend_name',
                'email as friend_email',
                'photo as friend_photo',
                DB::raw("IF(online=0,'下線中','上線中') as online"),
            )
            ->where('friends.type', '=', 1)
            ->where('users.id', '<>', auth('web')->user()->id)
            ->Where(function ($query) {
                $query->where('inviter_user_id', auth('web')->user()->id)
                    ->orWhere('invitee_user_id', auth('web')->user()->id);
            })
            ->get();

        return $rosters;
    }
    public function rosterPage()
    {
        $unread=false;
        foreach ($this->getRecords() as $record) {
            if ($record->unread>0) {
                $unread=true;
            }
        }
        $binding = [
            'title' => '好友名單',
            'id'=>auth('web')->user()->id,
            'name'=>auth('web')->user()->name,
            'photo'=>auth('web')->user()->photo,
            'unread'=>$unread,
            'rosters' => $this->getRosters(),
        ];
        return view('friend.friendList', $binding);
    }
    public function getRecords()
    {
        $records= DB::table('friends')
            ->join('users', function ($join) {
                $join->on('users.id', '=', 'friends.inviter_user_id')
                    ->orOn('users.id', '=', 'friends.invitee_user_id');
            })
            ->join(DB::raw('(select * from friends_message,
            (select max(created_at) as maxtime,
            count(IF(type = 0 AND user_id != '.auth('web')->user()->id.',true,null)) as unread
            from friends_message group by friend_id) c where friends_message.created_at=c.maxtime) as friends_message'), function ($join) {
                $join->on('friends_message.friend_id', '=', 'friends.id');
            })
            ->select(
                'friends.id as friend_id',
                'users.id as friend_userId',
                'name as friend_name',
                'photo as friend_photo',
                'friends_message.user_id as message_userId',
                'message',
                'unread',
                DB::raw("DATE_FORMAT(maxtime,'%m-%d') as date"),
                DB::raw("DATE_FORMAT(maxtime,'%H:%i') as time"),
            )
            ->where('users.id', '<>', auth('web')->user()->id)
            ->Where(function ($query) {
                $query->where('inviter_user_id', auth('web')->user()->id)
                    ->orWhere('invitee_user_id', auth('web')->user()->id);
            })
            ->orderBy('maxtime', 'desc')
            ->get();
        foreach ($records as &$record) {
            if (strpos($record->message, 'images') !== false) {
                if ($record->friend_userId==auth('web')->user()->id) {
                    $record->message = "照片已傳送";
                } else {
                    $record->message ="對方傳送了照片";
                }
            } else {
                $record->message=mb_substr(preg_replace('/<[^>]+>|&[^>]+;/', '', $record->message), 0, 12, 'utf8');
            }
            if (date("m-d")==$record->date) {
                $record->date="";
            } elseif (date("m-d", strtotime("-1 day"))==$record->date) {
                $record->date="昨天";
            } else {
                $record->time="";
            }
        }
        unset($record);
        return $records;
    }
    public function recordsPage()
    {
        $unread=false;
        foreach ($this->getRecords() as $record) {
            if ($record->unread>0) {
                $unread=true;
            }
        }
        $binding = [
            'title' => '聊天紀錄',
            'id'=>auth('web')->user()->id,
            'name'=>auth('web')->user()->name,
            'photo'=>auth('web')->user()->photo,
            'unread'=>$unread,
            'records' => $this->getRecords(),
        ];
        return view('friend.friendList', $binding);
    }
    public function getAppllys()
    {
        $appllys=DB::table('friends')
            ->join('users', function ($join) {
                $join->on('users.id', '=', 'friends.inviter_user_id')
                    ->orOn('users.id', '=', 'friends.invitee_user_id');
            })
            ->select('friends.id as friend_id', 'users.id as friend_userId', 'name as friend_name', 'email as friend_email')
            ->where('invitee_user_id', auth('web')->user()->id)
            ->where('users.id', '!=', auth('web')->user()->id)
            ->where('friends.type', 0)
            ->get();
        return  $appllys;
    }
    public function applysPage()
    {
        $unread=false;
        foreach ($this->getRecords() as $record) {
            if ($record->unread>0) {
                $unread=true;
            }
        }

        $binding = [
            'title' => '申請審核',
            'id'=>auth('web')->user()->id,
            'name'=>auth('web')->user()->name,
            'photo'=>auth('web')->user()->photo,
            'unread'=>$unread,
            'applys' => $this->getAppllys(),
        ];

        return view('friend.friendList', $binding);
    }
    public function getFriendData($friend_id, $user_id)
    {
        $friend = DB::table('friends')
            ->join('users', function ($join) {
                $join->on('users.id', '=', 'friends.inviter_user_id')
                    ->orOn('users.id', '=', 'friends.invitee_user_id');
            })
            ->select('users.id as friend_userId', 'users.name as friend_name', 'photo as friend_photo')
            ->where('users.id', '!=', $user_id)
            ->where('friends.id', $friend_id)
            ->Where(function ($query) use ($user_id) {
                $query->where('inviter_user_id', $user_id)
                    ->orWhere('invitee_user_id', $user_id);
            })
            ->first();
        return $friend;
    }
    public function getMessages($friend_id)
    {
        $messages = DB::table('friends_message')
                ->join('users', function ($join) {
                    $join->on('users.id', '=', 'friends_message.user_id');
                })
                ->select(
                    'friends_message.id',
                    'users.id as user_id',
                    'name',
                    'photo',
                    'message',
                    DB::raw("case
                    when DATE(NOW())=DATE(friends_message.created_at) then '今天'
                    when  DATE_SUB(DATE(NOW()),INTERVAL 1 DAY)=DATE(friends_message.created_at) then '昨天'
                    else  DATE(friends_message.created_at)
                    END AS date"),
                    DB::raw("DATE_FORMAT(friends_message.created_at,'%H:%i') as time"),
                    DB::raw("IF(friends_message.type=1,'已讀',null) as ready"),
                    'friends_message.created_at',
                )
                ->where('friends_message.friend_id', $friend_id)
                ->orderBy('friends_message.created_at', 'asc')
                ->get();

        //整理訊息格式
        $date = "";
        foreach ($messages as &$message) {
            $message->photo = asset($message->photo);
            if (strpos($message->message, 'images') !== false) {
                $message->message = asset($message->message);
            }
            if ($date != $message->date) {
                $date = $message->date;
            } else {
                $message->date = null;
            }
        }
        unset($message);
        return $messages;
    }
    public function getReadyMessages($friend_id, $user_id)
    {
        FriendMessage::where('friend_id', $friend_id)
        ->where('user_id', "!=", $user_id)
        ->update(['type' => 1]);
    }
    public function friendMessagePage($friend_id)
    {
        Artisan::call('view:clear');
        //進入更改訊息讀取狀態，並使用getReadyMessage、getRecordMessage更改已讀未讀及聊天紀錄
        event(new \App\Events\getReadyMessage($friend_id, $this->getReadyMessages($friend_id, auth('web')->user()->id)));
        //取得好友名稱、id、照片
        $friend = $this->getFriendData($friend_id, auth('web')->user()->id);
        if ($friend === null) {
            return response('404 Not Found', 404);
        } else {
            $unread=false;
            foreach ($this->getRecords() as $record) {
                if ($record->unread>0) {
                    $unread=true;
                }
            }
            //綑綁傳回blade的資料
            $binding = [
                'title' => $friend->friend_name,
                'name'=>auth('web')->user()->name,
                'photo'=>auth('web')->user()->photo,
                'friend_name'=>$friend->friend_name,
                'friend_photo'=>$friend->friend_photo,
                'unread'=>$unread,
                'messages' => $this->getMessages($friend_id),
            ];
            //帶著 $binding顯示friendMessage畫面
            return view('friend.friendMessage', $binding);
        }
    }
}
