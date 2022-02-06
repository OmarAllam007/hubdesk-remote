<?php


//use App\Http\Controllers\LetterGroupController;


Route::group(['prefix' => 'letters', 'as' => 'letters.'], function (\Illuminate\Routing\Router $r) {
    $r->get('', '\App\Http\Controllers\Letters\DashboardController@index')->name('index');
    $r->resource('letter-group', 'LetterGroupController');
    $r->resource('letter', 'LetterController');
    $r->resource('letter-field', 'LetterFieldController');
    $r->resource('signature', 'LetterSignatureController');
    $r->resource('header', 'LetterHeaderController');

    $r->get('approval/create/{businessUnit}', 'LetterApprovalsController@create')->name('approval.create');
    $r->get('approval/edit/{businessUnit}', 'LetterApprovalsController@edit')->name('approval.edit');

    $r->get('approval/business_unit/{businessUnit}', 'LetterApprovalsController@showApprovals')
        ->name('business-unit.approval.show');

    $r->resource('approval', 'LetterApprovalsController')
        ->except(['create', 'edit']);

    $r->get('generate-letter/{ticket}', [\App\Http\Controllers\LetterController::class, 'generateLetter'])->name('generate.pdf');
    $r->get('generate-letter-doc/{ticket}', [\App\Http\Controllers\LetterController::class, 'generateLetterDoc']);

    $r->post('create-letter-ticket', 'LetterController@createLetterTicket');

    Route::group(['prefix' => 'list', 'as' => 'list'], function (\Illuminate\Routing\Router $r) {
        $r->get('subgroups/{group}', [\App\Http\Controllers\LettersListController::class, 'subgroups']);
        $r->get('letters', [\App\Http\Controllers\LettersListController::class, 'letters']);
        $r->get('letter_fields/{letter}', [\App\Http\Controllers\LettersListController::class, 'fields']);
    });

    Route::get('/get-letter-content/{ticket}', 'LetterController@getLetterContent');

    Route::group(['prefix' => 'list'], function (\Illuminate\Routing\Router $r) {
        $r->get('letter_group', 'ListController@letter_group');
    });

    $r->post('/convert-to-letter','LetterController@convertToLetter');

    $r->get('/verify-letter/{ticketId}','LetterController@verifyLetterView')->name('verify-letter');
});