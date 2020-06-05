<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\User;
use Illuminate\Support\Facades\Auth;
use Validator;
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
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required',
            'c_password' => 'required|same:password',
            'type'=>'in:0,1',
        ]);

        if ($validator->fails()) {
            return response()->json(['error'=>[$validator->errors()->first()]]);
        }
        $input=$request->all();
        $user = User::create($input);
        return response()->json(['success'=>'註冊成功']);
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
