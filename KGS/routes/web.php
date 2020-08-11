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

use App\Http\Controllers\ListController;
use Illuminate\Routing\Router;

Route::group(['middleware' => 'auth'], function (Router $r) {
    Route::get('business-document/select_division', 'BusinessDocumentController@selectDivision')
        ->name('business_document.select_division');

    Route::get('business-document/division/{division}/select_business_unit', 'BusinessDocumentController@index')
        ->name('business_document.select_business_unit');

    Route::get('business-document/business_unit/{business_unit}/select_category', 'BusinessDocumentController@selectCategory')
        ->name('document.select_category');

    Route::get('business-document/{business_unit}/category/{category}/select_subcategory', 'BusinessDocumentController@selectSubcategory')
        ->name('document.select_subcategory');

    Route::get('business-document/{business_unit}/category/{category}/select_subcategory/{subcategory}/select_item', 'BusinessDocumentController@selectItem')
        ->name('document.select_item');

    Route::get('business-document/{business_unit}/category/{category}/select_subcategory/{subcategory}/select_item/{item}/requirements', 'BusinessDocumentController@checkForRequirements')
        ->name('document.check-requirements');

    Route::resource('business_unit/{folder}/document', 'DocumentController');
    Route::post('document/{folder}/move', 'DocumentController@move')->name('document.assign');

    Route::post('business-document/{business_unit}/category/{category}/select_subcategory/{subcategory}/select_item/{item?}', 'BusinessDocumentController@createTicket')
        ->name('document.create-ticket');

    Route::get('business-document/{business_unit}/roles', 'BusinessDocumentRolesController@show')
        ->name('document.roles.show');
    Route::post('business-document/{business_unit}/roles', 'BusinessDocumentRolesController@update')
        ->name('document.roles.submit');

    Route::get('business-document/business_unit/{business_unit}/modify-notifications', 'BusinessDocumentController@manageNotification')
        ->name('document.manage_notifications');
    Route::post('business-document/business_unit/{business_unit}/modify-notifications', 'BusinessDocumentController@saveNotification')
        ->name('document.manage_notifications');

    Route::post('/send-to-finance/{ticket}', 'KGSTicketController@sendToFinance')
        ->name('ticket.finance.send');


    Route::get('business-folders/{business_unit}', 'BusinessDocumentsFolderController@index')
        ->name('business_documents_folder.index');
    Route::get('business-folders/{business_unit}/create', 'BusinessDocumentsFolderController@create')
        ->name('business_documents_folder.create');
    Route::get('business-folders/{folder}/edit', 'BusinessDocumentsFolderController@edit')
        ->name('business_documents_folder.edit');
    Route::post('business-folders/{business_unit}/folder', 'BusinessDocumentsFolderController@store')
        ->name('business_documents_folder.store');
    Route::post('business-folders/{folder}', 'BusinessDocumentsFolderController@update')
        ->name('business_documents_folder.update');
    Route::delete('business-folders/{folder}', 'BusinessDocumentsFolderController@destroy')
        ->name('business_documents_folder.destroy');

    Route::get('download-attach/{attachment}', 'DocumentController@downloadAttachment')
        ->name('business_document.download');

    Route::get('create-ticket-check/{document}', 'BusinessDocumentController@createTicketCheck')->name('document.create_check');
});


Route::group(['prefix' => 'admin', 'middleware' => 'auth'], function (Router $r) {
    $r->get('', 'KGSAdminController@index')->name('admin.index');
    $r->get('categories', 'KGSCategoryController@index')->name('admin.category.index');
    $r->get('category/create', 'KGSCategoryController@create')->name('admin.category.create');
    $r->post('category', 'KGSCategoryController@store')->name('admin.category.store');
    $r->get('category/{category}/edit', 'KGSCategoryController@edit')->name('admin.category.edit');
    $r->get('category/{category}', 'KGSCategoryController@show')->name('admin.category.show');
    $r->post('category/{category}', 'KGSCategoryController@update')->name('admin.category.update');

    $r->get('subcategories', 'KGSSubcategoryController@index')->name('admin.subcategory.index');
    $r->get('subcategory/create', 'KGSSubcategoryController@create')->name('admin.subcategory.create');
    $r->post('subcategory', 'KGSSubcategoryController@store')->name('admin.subcategory.store');
    $r->get('subcategory/{subcategory}/edit', 'KGSSubcategoryController@edit')->name('admin.subcategory.edit');
    $r->get('subcategory/{subcategory}', 'KGSSubcategoryController@show')->name('admin.subcategory.show');
    $r->post('subcategory/{subcategory}', 'KGSSubcategoryController@update')->name('admin.subcategory.update');
    $r->delete('subcategory/{subcategory}', 'KGSSubcategoryController@destroy')->name('admin.subcategory.destroy');

    $r->get('items', 'KGSItemController@index')->name('admin.item.index');
    $r->get('item/create', 'KGSItemController@create')->name('admin.item.create');
    $r->post('item', 'KGSItemController@store')->name('admin.item.store');
    $r->get('item/{item}/edit', 'KGSItemController@edit')->name('admin.item.edit');
    $r->get('item/{item}', 'KGSItemController@show')->name('admin.item.show');
    $r->post('item/{item}', 'KGSItemController@update')->name('admin.item.update');
    $r->delete('item/{item}', 'KGSItemController@destroy')->name('admin.item.destroy');


    $r->get('business_unit', 'KGSBusinessUnitController@index')->name('admin.business_unit.index');
    $r->get('business_unit/edit', 'KGSBusinessUnitController@edit')->name('admin.business_unit.edit');
    $r->post('business_unit', 'KGSBusinessUnitController@update')->name('admin.business_unit.update');


});

