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
Route::get('business-document/business_unit/{business_unit}/select_category','BusinessDocumentController@selectCategory')->name('document.select_category');
Route::get('business-document/{business_unit}/category/{category}/select_subcategory','BusinessDocumentController@selectSubcategory')->name('document.select_subcategory');
Route::get('business-document/{business_unit}/category/{category}/select_subcategory/{subcategory}/select_item','BusinessDocumentController@selectItem')->name('document.select_item');
Route::get('business-document/{business_unit}/category/{category}/select_subcategory/{subcategory}/select_item/{item}/requirements','BusinessDocumentController@checkForRequirements')->name('document.check-requirements');
Route::resource('business_unit/{business_unit}/document','DocumentController');