<?php

use Illuminate\Http\Request;

Route::post('/login', 'PassportController@loginProcess');
Route::post('/register', 'PassportController@registerProcess');
Route::post('/forget', 'PassportController@forgetProcess');
Route::post('/reset', 'PassportController@resetProcess');

Route::middleware('auth:api')->post('/edit', 'PassportController@edit');
Route::middleware('auth:api')->get('/logout', 'PassportController@logout');

Route::group(['prefix' => 'friend'], function () {
    Route::delete('/roster/{friend_id}', 'FriendController@deleteRosterProcess');
    Route::post('/apply', 'FriendController@addApplyProcess');
    Route::put('/apply/{friend_id}', 'FriendController@agreeApplyProcess');
    Route::delete('/apply/{friend_id}', 'FriendController@refuseApplyProcess');
    Route::post('/chat/{friend_id}', 'FriendController@addMessageProcess');
    Route::put('/chat/{friend_id}', 'FriendController@checkReadyMessageProcess');
});
