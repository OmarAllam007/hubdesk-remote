<?php


//use App\Http\Controllers\LetterGroupController;


Route::group(['prefix' => 'letters', 'as' => 'letters.'], function (\Illuminate\Routing\Router $r) {
    $r->get('', '\App\Http\Controllers\Letters\DashboardController@index')->name('index');
    $r->resource('letter-group', 'LetterGroupController');
    $r->resource('letter', 'LetterController');
    $r->resource('letter-field', 'LetterFieldController');


    Route::group(['prefix'=> 'list', 'as' => 'list'], function (\Illuminate\Routing\Router $r) {
        $r->get('subgroups/{group}', [\App\Http\Controllers\LettersListController::class, 'subgroups']);
        $r->get('letters', [\App\Http\Controllers\LettersListController::class, 'letters']);
    });
});