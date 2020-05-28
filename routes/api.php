<?php

use Illuminate\Http\Request;


Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


Route::post('/login', 'PassportController@loginProcess');

Route::post('/register', 'PassportController@registerProcess');


Route::middleware('auth:api')->get('/logout', 'PassportController@logout');


Route::group(['prefix' => 'friend'], function () {
    Route::delete('/roster/{friend_id}', 'FriendController@deleteRosterProcess');
    Route::post('/apply', 'FriendController@addApplyProcess');
    Route::put('/apply/{friend_id}', 'FriendController@agreeApplyProcess');
    Route::delete('/apply/{friend_id}', 'FriendController@refuseApplyProcess');

    Route::post('/chat/{friend_id}', function () {
        return '好友聊天api';
    });
});
