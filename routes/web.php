<?php
Route::get('/test', function (Request $request) {
    return response()->json(['test'=>auth()->user()]);
});

Route::get('/', 'loginPageController@loginPage')->name('login')->middleware('login.auth');

Route::get('/register', 'loginPageController@registerPage')->middleware('login.auth');

Route::get('/reset', 'loginPageController@resetPage')->middleware('login.auth');


Route::group(['middleware' => ['friend.auth'], 'prefix' => 'friend'], function () {
    Route::get('/test', 'friendPageController@getApllys');
    Route::get('/roster', 'friendPageController@rosterPage');
    Route::get('/record', 'friendPageController@recordsPage');
    Route::get('/apply', 'friendPageController@applysPage');
    Route::get('/chat/{friedn_id}', 'friendPageController@friendMessagePage');
});
