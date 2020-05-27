<?php

use Illuminate\Http\Request;

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::post('/login', function () {
    return '登入API';
});

Route::post('/register', function () {
    return '註冊API';
});
Route::get('/logout', function () {
    return '登出API';
});

Route::group(['prefix' => 'friend'], function () {
    Route::delete('/{friend_id}/delete', function () {
        return '刪除好友&拒絕邀請API';
    });
    Route::post('/apply-for/{friend_id}', function () {
        return '好友申請api';
    });
    Route::put('/apply-for/{friend_id}', function () {
        return '同意好友申請api';
    });
    Route::post('/chat/{friend_id}', function () {
        return '好友聊天api';
    });
});
