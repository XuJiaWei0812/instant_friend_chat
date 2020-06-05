<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use DB;
use Illuminate\Support\Facades\Auth;
use App\user;
use App\Friend;
use App\FriendMessage;
use Session;

class FriendController extends Controller
{
    public function addApplyProcess(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
        ]);

        if ($validator->fails()) {
            //傳送失敗JSON回應
            return response()->json(['error' => $validator->errors()->all()]);
        } else {
            $input = request()->all();

            $user=User::where('email', $input['email'])->first();

            if (empty($user)) {
                return response()->json(['error' => ['查無此會員帳號']]);
            } elseif (!empty($user) && $user->id==auth('api')->user()->id) {
                return response()->json(['error'=> ['請勿輸入自己的帳號']]);
            } else {
                $friend=Friend::Where(function ($query) {
                    $query->where('inviter_user_id', '=', auth('api')->user()->id)
                    ->orWhere('invitee_user_id', '=', auth('api')->user()->id);
                })->Where(function ($query) use ($user) {
                    $query->where('inviter_user_id', '=', $user->id)
                    ->orWhere('invitee_user_id', '=', $user->id);
                })->first();

                if (!empty($friend)) {
                    if ($friend->type==0) {
                        return response()->json(['error' => ['等待對方同意']]);
                    } else {
                        return response()->json(['error' => ['已與對方為朋友']]);
                    }
                }
            }
            $data=[
                    'inviter_user_id' => auth('api')->user()->id,
                    'invitee_user_id' => $user->id,
                ];
            $friend = Friend::create($data);
            return response()->json(['success' => '好友申請已送出']);
        }
    }
    public function agreeApplyProcess($friend_id)
    {
        $friend=Friend::where('id', $friend_id)->where('invitee_user_id', auth('api')->user()->id)->first();
        if (empty($friend)) {
            return response()->json(['error' => '並無此好友申請']);
        } else {
            Friend::where('id', $friend_id)->update(array('type' => 1));
            return response()->json(['success' => '同意好友申請']);
        }
    }
    public function refuseApplyProcess($friend_id)
    {
        $friend=Friend::where('id', $friend_id)->where('invitee_user_id', auth('api')->user()->id)->first();

        if (empty($friend)) {
            return response()->json(['error' => '並無此好友申請']);
        } else {
            Friend::where('id', $friend_id)->delete();
            return response()->json(['success' => '拒絕好友申請']);
        }
    }
    public function deleteRosterProcess($friend_id)
    {
        $friend=Friend::where('id', $friend_id)->first();
        if (empty($friend)) {
            return response()->json(['error' => '查無此好友']);
        } else {
            Friend::where('id', $friend_id)->delete();
            return response()->json(['success' => '成功刪除此好友']);
        }
    }
    public function addMessageProcess(Request $request, $fid)
    {
        $input = request()->all();
        if (isset($input['file'])) {
            $input['message']=$input['file'];
        }
        $validator = Validator::make($input, [
            'message' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->all()]);
        } else {
            event(new \App\Events\createFriendMessage($fid, auth('api')->user()->id, $input));
        }
    }
    public function checkReadyMessageProcess(Request $request, $fid)
    {
        $input = request()->all();

        $friendr_ready = FriendMessage::where('friend_id', $fid)
            ->where('user_id', '!=', auth('api')->user()->id)
            ->update(['type' => 1]);
        event(new \App\Events\getReadyMessage($fid));
        event(new \App\Events\getRecordMessage(auth('api')->user()->id));
        return response()->json(['success' => '確認訊息功能通過']);
    }
}
