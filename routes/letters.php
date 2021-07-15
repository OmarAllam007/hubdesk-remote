<?php


//use App\Http\Controllers\LetterGroupController;

Route::group(['prefix' => 'letters','as' => 'letters.'], function (\Illuminate\Routing\Router $r) {
    $r->get('', '\App\Http\Controllers\Letters\DashboardController@index')->name('index');
    $r->resource('letter-group', 'LetterGroupController');
    $r->resource('letter', 'LetterController');
});