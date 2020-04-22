<?php
use Illuminate\Support\Facades\Route;

Route::get('/','CruiseController@index')->name('cruise.admin.index');

Route::match(['get'],'/create','CruiseController@create')->name('cruise.admin.create');
Route::match(['get'],'/edit/{id}','CruiseController@edit')->name('cruise.admin.edit');

Route::post('/store/{id}','CruiseController@store')->name('cruise.admin.store');

Route::get('/getForSelect2','CruiseController@getForSelect2')->name('cruise.admin.getForSelect2');
Route::post('/bulkEdit','CruiseController@bulkEdit')->name('cruise.admin.bulkEdit');

Route::match(['get'],'/category','CategoryController@index')->name('cruise.admin.category.index');
Route::match(['get'],'/category/edit/{id}','CategoryController@edit')->name('cruise.admin.category.edit');
Route::post('/category/store/{id}','CategoryController@store')->name('cruise.admin.category.store');

Route::match(['get'],'/attribute','AttributeController@index')->name('cruise.admin.attribute.index');
Route::match(['get'],'/attribute/edit/{id}','AttributeController@edit')->name('cruise.admin.attribute.edit');
Route::post('/attribute/store/{id}','AttributeController@store')->name('cruise.admin.attribute.store');

Route::match(['get'],'/attribute/term_edit','AttributeController@terms')->name('cruise.admin.attribute.term.index');
Route::match(['get'],'/attribute/term_edit/edit/{id}','AttributeController@term_edit')->name('cruise.admin.attribute.term.edit');
Route::post('/attribute/term_store/{id}','AttributeController@term_store')->name('cruise.admin.attribute.term.store');


Route::group(['prefix'=>'availability'],function(){
    Route::get('/','AvailabilityController@index')->name('cruise.admin.availability.index');
    Route::get('/loadDates','AvailabilityController@loadDates')->name('cruise.admin.availability.loadDates');
    Route::get('/store','AvailabilityController@store')->name('cruise.admin.availability.store');
});