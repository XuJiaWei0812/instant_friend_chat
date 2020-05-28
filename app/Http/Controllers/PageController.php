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
        $row_per_page = 10;

        $friendRosterPaginate = DB::table('friends')
            ->join('users', function ($join) {
                $join->on('users.id', '=', 'friends.inviter_user_id')
                    ->orOn('users.id', '=', 'friends.invitee_user_id');
            })
            ->select('friends.id as fid', 'users.id as inviter_id', 'name as inviter_name', 'email as inviter_email', 'online')
            ->where('friends.type', '=', 1)
            ->where('users.id', '<>', 1)
            ->Where(function ($query) {
                $query->where('inviter_user_id', 1)
                    ->orWhere('invitee_user_id', 1);
            })
            ->paginate($row_per_page);

        $binding = [
            'title' => '好友名單',
            // 'friendRosterPaginate' => $friendRosterPaginate,
        ];

         return view('friendList', $binding);

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
            ->select('friends.id as fid','name','photo','message','unread','maxtime')
            ->where('users.id', '<>', 1)
            ->Where(function ($query) {
                $query->where('inviter_user_id', 1)
                    ->orWhere('invitee_user_id', 1);
            })
            ->orderBy('maxtime', 'desc')
            ->get();

        $binding = [
            'title' => '聊天紀錄',
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
            ->select('friends.id as fid', 'users.id as inviter_id', 'name as inviter_name', 'email as inviter_email')
            ->where('invitee_user_id', 1)
            ->where('users.id', '!=', 1)
            ->where('friends.type', 0)
            ->get();

        $binding = [
            'title' => '申請審核',
            'friendApplys' => $friendApplys,
        ];

        return response()->json(['success' => $friendApplyPage]);
    }
}
