<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class loginPageController extends Controller
{
    public function loginPage()//登入會員畫面
    {
        $binding = [
            'title' => '登入會員',
        ];
        return view('login', $binding);
    }
    public function registerPage()//註冊會員畫面
    {
        $binding = [
            'title' => '註冊會員',
        ];

        return view('register', $binding);
    }
    public function resetPage()//重設密碼畫面
    {
        $binding = [
            'title' => '重設密碼',
        ];

        return view('reset', $binding);
    }
}
