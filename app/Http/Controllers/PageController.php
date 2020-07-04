<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\FriendMessage;
use Cache;
use Illuminate\Support\Facades\Artisan;

class PageController extends Controller
{
    public function loginPage()
    {
        $binding = [
            'title' => '登入會員',
        ];
        return view('login', $binding);
    }
    public function registerPage()
    {
        $binding = [
            'title' => '註冊會員',
        ];

        return view('register', $binding);
    }
    public function resetPage()
    {
        $binding = [
            'title' => '重設密碼',
        ];

        return view('reset', $binding);
    }
    public function friendRosterPage()
    {
        $friendRosters = DB::table('friends')
            ->join('users', function ($join) {
                $join->on('users.id', '=', 'friends.inviter_user_id')
                    ->orOn('users.id', '=', 'friends.invitee_user_id');
            })
            ->select(
                'friends.id as fid',
                'users.id as fu_id',
                'name as fu_name',
                'email as fu_email',
                'photo',
                DB::raw("IF(online=0,'下線中','上線中') as online"),
            )
            ->where('friends.type', '=', 1)
            ->where('users.id', '<>', auth('web')->user()->id)
            ->Where(function ($query) {
                $query->where('inviter_user_id', auth('web')->user()->id)
                    ->orWhere('invitee_user_id', auth('web')->user()->id);
            })
            ->get();

        $binding = [
            'title' => '好友名單',
            'name'=>auth('web')->user()->name,
            'photo'=>auth('web')->user()->photo,
            'id'=>auth('web')->user()->id,
            'friendRosters' => $friendRosters,
        ];

        return view('friend.friendList', $binding);
    }
    public function friendRecordPage()
    {
        $friendRecords = DB::table('friends')
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
                'friends.id as fid',
                'friends_message.user_id as fm_uid',
                'users.id as uid',
                'name',
                'photo',
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

        foreach ($friendRecords as &$friendRecord) {
            if (strpos($friendRecord->message, 'images') !== false) {
                if ($friendRecord->fm_uid==auth('web')->user()->id) {
                    $friendRecord->message = "照片已傳送";
                } else {
                    $friendRecord->message = $friendRecord->name."傳送了照片";
                }
            } else {
                $friendRecord->message=mb_substr(preg_replace('/<[^>]+>|&[^>]+;/', '', $friendRecord->message), 0, 12, 'utf8');
            }
            if (date("m-d")==$friendRecord->date) {
                $friendRecord->date="";
            } elseif (date("m-d", strtotime("-1 day"))==$friendRecord->date) {
                $friendRecord->date="昨天";
            } else {
                $friendRecord->time="";
            }
        }
        unset($friendRecord);

        $binding = [
            'title' => '聊天紀錄',
            'name'=>auth('web')->user()->name,
            'photo'=>auth('web')->user()->photo,
            'id'=>auth('web')->user()->id,
            'friendRecords' => $friendRecords,
        ];

        return view('friend.friendList', $binding);
    }
    public function friendApplyPage()
    {
        $friendApplys = DB::table('friends')
            ->join('users', function ($join) {
                $join->on('users.id', '=', 'friends.inviter_user_id')
                    ->orOn('users.id', '=', 'friends.invitee_user_id');
            })
            ->select('friends.id as fid', 'users.id as fu_id', 'name as fu_name', 'email as fu_email')
            ->where('invitee_user_id', auth('web')->user()->id)
            ->where('users.id', '!=', auth('web')->user()->id)
            ->where('friends.type', 0)
            ->get();

        $binding = [
            'title' => '申請審核',
            'name'=>auth('web')->user()->name,
            'photo'=>auth('web')->user()->photo,
            'id'=>auth('web')->user()->id,
            'friendApplys' => $friendApplys,
        ];

        return view('friend.friendList', $binding);
    }
    public function friendMessagePage($fid)
    {
        Artisan::call('view:clear');
        //進入更改訊息讀取狀態，並使用getReadyMessage、getRecordMessage更改已讀未讀及聊天紀錄
        FriendMessage::where('friend_id', $fid)
        ->where('user_id', "!=", auth('web')->user()->id)
        ->update(['type' => 1]);
        event(new \App\Events\getReadyMessage($fid));
        event(new \App\Events\getRecordMessage(auth('web')->user()->id));
        //取得好友名稱、id、照片
        $friend = DB::table('friends')
            ->join('users', function ($join) {
                $join->on('users.id', '=', 'friends.inviter_user_id')
                    ->orOn('users.id', '=', 'friends.invitee_user_id');
            })
            ->select('users.id as fuid', 'users.name as fu_name', 'photo as fu_photo')
            ->where('users.id', '!=', auth('web')->user()->id)
            ->where('friends.id', $fid)
            ->Where(function ($query) {
                $query->where('inviter_user_id', auth('web')->user()->id)
                    ->orWhere('invitee_user_id', auth('web')->user()->id);
            })
            ->first();
        if ($friend === null) {
            return response('404 Not Found', 404);
        } else {
            //取得好友對話訊息
            $friendMessages = DB::table('friends_message')
                ->join('users', function ($join) {
                    $join->on('users.id', '=', 'friends_message.user_id');
                })
                ->select(
                    'friends_message.id',
                    'users.id as uid',
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
                ->where('friends_message.friend_id', $fid)
                ->orderBy('friends_message.created_at', 'asc')
                ->get();

            //整理訊息格式
            $date = "";
            foreach ($friendMessages as &$friendMessage) {
                $friendMessage->photo = asset($friendMessage->photo);
                if (strpos($friendMessage->message, 'images') !== false) {
                    $friendMessage->message = asset($friendMessage->message);
                }
                if ($date != $friendMessage->date) {
                    $date = $friendMessage->date;
                } else {
                    $friendMessage->date = null;
                }
            }
            unset($friendMessage);
            //綑綁傳回blade的資料
            $binding = [
                'title' => $friend->fu_name,
                'name'=>auth('web')->user()->name,
                'photo'=>auth('web')->user()->photo,
                'id'=>auth('web')->user()->id,
                'fu_name'=>$friend->fu_name,
                'fu_photo'=>$friend->fu_photo,
                'friendMessages' => $friendMessages,
            ];
            //帶著 $binding顯示friendMessage畫面
            return view('friend.friendMessage', $binding);
        }
    }
}
