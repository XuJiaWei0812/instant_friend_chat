<?php

Route::get('/', function () {
    return '登入';
});

Route::get('/register', function () {
    return '註冊';
});

Route::group(['prefix' => 'friend'], function () {
    Route::get('/', function () {
        return '好友名單';
    });
    Route::get('/chat-history', function () {
        return '聊天紀錄';
    });
    Route::get('/apply-for', function () {
        return '好友申請';
    });
    Route::get('/chat/{friedn_id}', function () {
        return '跟XXX聊天';
    });
});
