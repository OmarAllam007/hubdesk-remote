<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

//Route::get('index','');
Route::get('business-document','BusinessDocumentController@index')->name('business_document');

Route::get('business-document/business_unit/{business_unit}/select_category','BusinessDocumentController@selectCategory')
    ->name('document.select_category');

Route::get('business-document/{business_unit}/category/{category}/select_subcategory','BusinessDocumentController@selectSubcategory')
    ->name('document.select_subcategory');

Route::get('business-document/{business_unit}/category/{category}/select_subcategory/{subcategory}/select_item','BusinessDocumentController@selectItem')
    ->name('document.select_item');

Route::get('business-document/{business_unit}/category/{category}/select_subcategory/{subcategory}/select_item/{item}/requirements','BusinessDocumentController@checkForRequirements')
    ->name('document.check-requirements');

Route::resource('business_unit/{folder}/document','DocumentController');

Route::post('business-document/{business_unit}/category/{category}/select_subcategory/{subcategory}/select_item/{item?}','BusinessDocumentController@createTicket')
    ->name('document.create-ticket');

Route::get('business-document/{business_unit}/roles','BusinessDocumentRolesController@show')->name('document.roles.show');
Route::post('business-document/{business_unit}/roles','BusinessDocumentRolesController@update')->name('document.roles.submit');

Route::get('business-document/business_unit/{business_unit}/modify-notifications','BusinessDocumentController@manageNotification')
    ->name('document.manage_notifications');

Route::post('business-document/business_unit/{business_unit}/modify-notifications','BusinessDocumentController@saveNotification')
    ->name('document.manage_notifications');

Route::post('/send-to-finance/{ticket}','KGSTicketController@sendToFinance')->name('ticket.finance.send');

Route::get('business-folders/{business_unit}','BusinessDocumentsFolderController@index')->name('business_documents_folder.index');
Route::get('business-folders/{business_unit}/create','BusinessDocumentsFolderController@create')->name('business_documents_folder.create');
Route::get('business-folders/{folder}/edit','BusinessDocumentsFolderController@edit')->name('business_documents_folder.edit');
Route::post('business-folders/{business_unit}/folder','BusinessDocumentsFolderController@store')->name('business_documents_folder.store');
Route::post('business-folders/{folder}','BusinessDocumentsFolderController@update')->name('business_documents_folder.update');
Route::delete('business-folders/{folder}','BusinessDocumentsFolderController@destroy')->name('business_documents_folder.destroy');

Route::get('download-attach/{attachment}','DocumentController@downloadAttachment')->name('business_document.download');