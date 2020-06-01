<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

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
    public function friendRosterPage()
    {
        $friendRosters = DB::table('friends')
            ->join('users', function ($join) {
                $join->on('users.id', '=', 'friends.inviter_user_id')
                    ->orOn('users.id', '=', 'friends.invitee_user_id');
            })
            ->select('friends.id as fid', 'users.id as fu_id', 'name as fu_name', 'email as fu_email', 'photo', 'online')
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
            count(IF(type = 0 AND user_id != 1,true,null)) as unread
            from friends_message group by friend_id) c where friends_message.created_at=c.maxtime) as friends_message'), function ($join) {
                $join->on('friends_message.friend_id', '=', 'friends.id');
            })
            ->select('friends.id as fid', 'name', 'photo', 'message', 'unread', 'maxtime')
            ->where('users.id', '<>', 1)
            ->Where(function ($query) {
                $query->where('inviter_user_id', 1)
                    ->orWhere('invitee_user_id', 1);
            })
            ->orderBy('maxtime', 'desc')
            ->get();

        $binding = [
            'title' => '聊天紀錄',
            'name'=>auth('web')->user()->name,
            'photo'=>auth('web')->user()->photo,
            'friendRecords' => $friendRecords,
        ];

        return response()->json(['success' => $friendRecords]);
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
            'friendApplys' => $friendApplys,
        ];

        return view('friend.friendList', $binding);
    }
    public function friendMessagePage($fid)
    {
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
            $friendMessages = DB::table('friends_message')
                ->join('users', function ($join) {
                    $join->on('users.id', '=', 'friends_message.user_id');
                })
                ->select(
                    'users.id as uid',
                    'name',
                    'photo',
                    'message',
                    DB::raw("DATE_FORMAT(friends_message.created_at,'%Y-%m-%d') as date"),
                    DB::raw("DATE_FORMAT(friends_message.created_at,'%H:%i') as time"),
                    'friends_message.created_at',
                    'friends_message.type as ready'
                )
                ->where('friends_message.friend_id', $fid)
                ->orderBy('friends_message.created_at', 'asc')
                ->get();
            $date = "";
            foreach ($friendMessages as &$friendMessage) {
                $friendMessage->photo = asset($friendMessage->photo);

                if ($friendMessage->ready == '1') {
                    $friendMessage->ready = "已讀";
                } else {
                    $friendMessage->ready = "";
                }

                if (strpos($friendMessage->message, 'images') !== false) {
                    $friendMessage->message = asset($friendMessage->message);
                }

                if ($date != $friendMessage->date) {
                    $date = $friendMessage->date;
                    if ($date == date("Y-m-d")) {
                        $friendMessage->date = "今天";
                    }
                } else {
                    $friendMessage->date = "";
                }
            }
            unset($friendMessage);

            $binding = [
                'title' => $friend->fu_name,
                'name'=>auth('web')->user()->name,
                'photo'=>auth('web')->user()->photo,
                'fu_name'=>$friend->fu_name,
                'fu_photo'=>$friend->fu_photo,
                'friendMessages' => $friendMessages,
            ];

            return view('friend.friendMessage', $binding);
        }
    }
}
