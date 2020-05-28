<?php
Route::get('/test', function (Request $request) {
   return response()->json(['test'=>auth()->user()]);
});

Route::get('/', 'PageController@loginPage')->name('login');;

Route::get('/register', 'PageController@registerPage');

Route::group(['prefix' => 'friend'], function () {
    Route::get('/roster', 'PageController@friendRosterPage');
    Route::get('/record', 'PageController@friendRecordPage');
    Route::get('/apply', 'PageController@friendApplyPage');
    Route::get('/chat/{friedn_id}', function () {
        return '跟XXX聊天';
    });
});
