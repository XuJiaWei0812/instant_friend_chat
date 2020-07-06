<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\User;
use DB;
use Illuminate\Support\Facades\Auth;
use Validator;
use Mail;
use Image;
use Session;

class PassportController extends Controller
{
    public function __construct()
    {
        $this->middleware('web');
    }
    public function registerProcess(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:users',
            'photo' => ['file', 'image', 'max:10240'],
            'email' => 'required|email|unique:users',
            'password' => 'required',
            'password_confirmation' => 'required|same:password',
            'type'=>'in:0,1',
        ]);

        if ($validator->fails()) {
            return response()->json(['error'=>[$validator->errors()->first()]]);
        } else {
            $input=$request->all();
            if (isset($input['photo'])) {
                $photo = $input['photo']; //有上傳圖片
                $file_extension = $photo->getClientOriginalExtension(); //取得副檔名
                $file_name = uniqid() . '.' . $file_extension;
                $file_relative_path = 'images/' . $file_name;
                $file_path = public_path($file_relative_path);
                $image = Image::make($photo)->fit(348, 348)->save($file_path);
                $input['photo'] = $file_relative_path;
            }
            $input["password"]=bcrypt($input["password"]);
            $user = User::create($input);
            return response()->json(['success'=>'註冊成功']);
        }
    }
    public function loginProcess(Request $request)
    {
        if (Auth::attempt(['email' => request('email'), 'password' => request('password')])) {
            $user = Auth::user();
            $user->online=1;
            $user->save();
            $success['token'] =  $user->createToken('token')->accessToken;
            $success['uid']=$user->id;
            $success['message']=$user->name." 登入成功 ";
            event(new \App\Events\checkLogin());
            return response()->json(['success' => $success]);
        } else {
            return response()->json(['error'=>['信箱或密碼錯誤']]);
        }
    }
    public function forgetProcess(Request $request)
    {
        $input=$request->all();
        $user = User::where("email", $input["checkEmail"])->pluck('email');
        if (sizeof($user)<1) {
            return response()->json(['error'=>["查無此電子郵件"]]);
        } else {
            //產生亂數認證碼
            $str="QWERTYUIOPASDFGHJKLZXCVBNM1234567890qwertyuiopasdfghjklzxcvbnm";
            str_shuffle($str);
            $captcha=substr(str_shuffle($str), 26, 5);
            User::where("email", $input["checkEmail"])->update(['captcha'=>$captcha]);
            //送出認證碼信件
            $mail_binding = [
                'toEmail' => $input['checkEmail'],
                'captcha' => $captcha,
            ];
            Mail::send(
                'emailPost',
                $mail_binding,
                function ($mail) use ($mail_binding) {
                    //收件人
                    $mail->to($mail_binding['toEmail']);
                    // //寄件人
                    // $mail->from("TEST客服中心");
                    //郵件主旨
                    $mail->subject($mail_binding['toEmail'].'請重新設定密碼');
                }
            );
            return response()->json(['sucess'=>["請前往確認信箱"]]);
        }
    }
    public function resetProcess(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'captcha' => 'required|min:5|max:5',
            'password' => 'required',
            'password_confirmation' => 'required|same:password',
        ]);

        if ($validator->fails()) {
            return response()->json(['error'=>[$validator->errors()->first()]]);
        } else {
            $input=$request->all();
            $count = User::where("captcha", $input["captcha"])->count();
            if ($count>0) {
                $password = bcrypt($input["password"]);
                $user = User::where("captcha", $input["captcha"])->update(['password'=>  $password,'captcha'=>null]);
                return response()->json(['sucess'=>["更改密碼完成"]]);
            } else {
                return response()->json(['error'=>["驗證碼錯誤"]]);
            }
        }
    }
    public function edit(Request $request)
    {
        if (Auth::check()) {
            $validator = Validator::make($request->all(), [
             'name' => 'required|unique:users',
             'photo' => ['file', 'image', 'max:10240'],
            ]);
            if ($validator->fails()) {
                return response()->json(['error'=>[$validator->errors()->first()]]);
            } else {
                $input=$request->all();
                if (isset($input['photo'])) {
                    $photo = $input['photo']; //有上傳圖片
                    $file_extension = $photo->getClientOriginalExtension(); //取得副檔名
                    $file_name = uniqid() . '.' . $file_extension;
                    $file_relative_path = 'images/' . $file_name;
                    $file_path = public_path($file_relative_path);
                    $image = Image::make($photo)->fit(348, 348)->save($file_path);
                    $input['photo'] = $file_relative_path;
                }
                User::where("id",Auth::user()->id)->update($input);
                return response()->json(['success'=>["編輯成功"]]);
            }
        }
    }
    public function logout(Request $request)
    {
        if (Auth::check()) {
            $user = Auth::user();
            $user->online=0;
            $user->save();
            Auth::user()->AauthAcessToken()->delete();
            Auth::guard('web')->logout();
            event(new \App\Events\checkLogin());
            return response()->json(['success' => '成功登出']);
        }
    }
}
