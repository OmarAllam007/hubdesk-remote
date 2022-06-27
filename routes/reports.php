<?php

Route::group(['prefix' => 'reports'], function () {
    Route::get('query/create', 'QueryReportController@create')->name('reports.query.create');
    Route::post('query/store', 'QueryReportController@store')->name('reports.query.store');
    Route::get('query/{report}/edit', 'QueryReportController@edit')->name('reports.query.edit');
    Route::post('query/{report}/update', 'QueryReportController@update')->name('reports.query.update');
    Route::get('query/{report}/show', 'QueryReportController@show')->name('reports.query_report.show');

    Route::resource('folder', 'ReportFolderController');

    Route::get('custom_report/create', 'CustomReportController@create')->name('reports.custom_report.create');
    Route::post('custom_report/store', 'CustomReportController@store')->name('reports.custom_report.store');
    Route::get('custom_report/{report}/edit', 'CustomReportController@edit')->name('reports.custom_report.edit');
    Route::post('custom_report/{report}/update', 'CustomReportController@update')->name('reports.custom_report.update');
    Route::get('custom_report/{report}/show', 'CustomReportController@show')->name('reports.custom_report.show');


    Route::get('scheduled_report/all', 'ScheduledReportController@index')->name('reports.scheduled_report.index');
    Route::get('scheduled_report/create', 'ScheduledReportController@create')->name('reports.scheduled_report.create');
    Route::post('scheduled_report/store', 'ScheduledReportController@store')->name('reports.scheduled_report.store');
    Route::get('scheduled_report/{report}/edit', 'ScheduledReportController@edit')->name('reports.scheduled_report.edit');
    Route::post('scheduled_report/{report}/update', 'ScheduledReportController@update')->name('reports.scheduled_report.update');
    Route::get('scheduled_report/{report}/show', 'ScheduledReportController@show')->name('reports.scheduled_report.show');
    Route::post('scheduled_report/{report}/execute', 'ScheduledReportController@execute')->name('reports.scheduled_report.execute');
});
