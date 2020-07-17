<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use DB;
use Image;
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
    public function addMessageProcess(Request $request, $friend_id)
    {
        $input = request()->all();

        $validator = Validator::make($input, [
            'file' => ['file', 'image', 'max:10240'],
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->all()]);
        } else {
            if (isset($input['file'])) {
                $photo = $input['file']; //有上傳圖片
                $file_extension = $photo->getClientOriginalExtension(); //取得副檔名
                $file_name = uniqid() . '.' . $file_extension;
                $file_relative_path = 'images/' . $file_name;
                $file_path = public_path($file_relative_path);
                $image = Image::make($photo)->fit(390, 300)->save($file_path);
                $input['message'] = $file_relative_path;
            }
            $input['friend_id']=$friend_id;
            $input['user_id']=auth('api')->user()->id;
            $add_message=FriendMessage::create($input);
            $new_message=DB::table('friends_message')
            ->join('users', function ($join) {
                $join->on('users.id', '!=', 'friends_message.user_id');
            })
            ->join('friends', function ($join) {
                $join->on('friends.id', '=', 'friends_message.friend_id');
                $join->on(function ($query) {
                    $query ->on('users.id', '=', 'friends.inviter_user_id')
                ->oron('users.id', '=', 'friends.invitee_user_id');
                });
            })
             ->select(
                 'friends_message.id',
                 'friends_message.friend_id as friend_id',
                 'users.id as friend_userId',
                 'photo as friend_photo',
                 'name as friend_name',
                 'friends_message.user_id as message_userId',
                 'message',
                 DB::raw("DATE_FORMAT(friends_message.created_at,'%H:%i') as time"),
             )
             ->where('friend_id', $add_message->friend_id)
             ->where('friends_message.id', $add_message->id)
             ->first();
            $new_message->friend_photo = asset($new_message->friend_photo);
            if (strpos($new_message->message, 'images') !== false) {
                $new_message->message = asset($new_message->message);
            }
            event(new \App\Events\createFriendMessage($new_message));
        }
    }
    public function getReadys($friend_id, $message_id, $user_id)
    {
        FriendMessage::where('friend_id', $friend_id)
        ->where('user_id', "!=", $user_id)
        ->update(['type' => 1]);
        $ready_message =FriendMessage::select('id')->where('friend_id', $friend_id)->where('type', 1)->where('id', $message_id)->get();
        return $ready_message;
    }
    public function getRecords($friend_userId)
    {
        $records= DB::table('friends')
            ->join('users', function ($join) {
                $join->on('users.id', '=', 'friends.inviter_user_id')
                    ->orOn('users.id', '=', 'friends.invitee_user_id');
            })
            ->join(DB::raw('(select * from friends_message,
            (select max(created_at) as maxtime,
            count(IF(type = 0 AND user_id != '.$friend_userId.',true,null)) as unread
            from friends_message group by friend_id) c where friends_message.created_at=c.maxtime) as friends_message'), function ($join) {
                $join->on('friends_message.friend_id', '=', 'friends.id');
            })
            ->select(
                'friends.id as friend_id',
                'users.id as friend_userId',
                'name as friend_name',
                'photo as friend_photo',
                'friends_message.id as message_id',
                'friends_message.user_id as message_userId',
                'message',
                'unread',
                DB::raw("DATE_FORMAT(maxtime,'%m-%d') as date"),
                DB::raw("DATE_FORMAT(maxtime,'%H:%i') as time"),
            )
            ->where('users.id', '<>', $friend_userId)
            ->Where(function ($query) use ($friend_userId) {
                $query->where('inviter_user_id', $friend_userId)
                    ->orWhere('invitee_user_id', $friend_userId);
            })
            ->orderBy('maxtime', 'desc')
            ->get();
        foreach ($records as &$record) {
            if (strpos($record->message, 'images') !== false) {
                if ($record->friend_userId==$friend_userId) {
                    $record->message = "照片已傳送";
                } else {
                    $record->message ="對方傳送了照片";
                }
            } else {
                $record->message=mb_substr(preg_replace('/<[^>]+>|&[^>]+;/', '', $record->message), 0, 12, 'utf8');
            }
            $record->friend_photo=asset($record->friend_photo);
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
    public function checkReadyMessageProcess(Request $request, $friend_id)
    {
        $friendr_ready = FriendMessage::where('friend_id', $friend_id)
            ->where('user_id', '!=', auth('api')->user()->id)
            ->update(['type' => 1]);
        event(new \App\Events\getReadyMessage($friend_id, $this->getReadys($friend_id, $request['message_id'], auth('api')->user()->id)));
        event(new \App\Events\getRecordMessage($request['friend_userId'], $this->getRecords($request['friend_userId'])));
        return response()->json(['success' => '確認訊息功能通過']);
    }
}
