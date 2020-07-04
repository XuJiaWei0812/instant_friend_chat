<?php
Route::get('/test', function (Request $request) {
    return response()->json(['test'=>auth()->user()]);
});

Route::get('/', 'PageController@loginPage')->name('login')->middleware('login.auth');

Route::get('/register', 'PageController@registerPage')->middleware('login.auth');

Route::get('/reset', 'PageController@resetPage')->middleware('login.auth');


Route::group(['middleware' => ['friend.auth'], 'prefix' => 'friend'], function () {
    Route::get('/roster', 'PageController@friendRosterPage');
    Route::get('/record', 'PageController@friendRecordPage');
    Route::get('/apply', 'PageController@friendApplyPage');
    Route::get('/chat/{friedn_id}', 'PageController@friendMessagePage');
});
