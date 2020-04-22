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
use Illuminate\Support\Facades\Route;
Route::group(['prefix'=>'user/cruise'],function(){
    Route::match(['get',],'/','ManageCruiseController@manageCruise')->name('cruise.vendor.list');
    Route::match(['get',],'/create','ManageCruiseController@createCruise')->name('cruise.vendor.create');
    Route::match(['get',],'/edit/{slug}','ManageCruiseController@editCruise')->name('cruise.vendor.edit');
    Route::match(['get','post'],'/del/{slug}','ManageCruiseController@deleteCruise')->name('cruise.vendor.delete');
    Route::match(['post'],'/store/{slug}','ManageCruiseController@store')->name('cruise.vendor.store');
    Route::get('/booking-report','ManageCruiseController@bookingReport')->name("cruise.vendor.booking_report");

    Route::group(['prefix'=>'availability'],function(){
        Route::get('/','AvailabilityController@index')->name('cruise.vendor.availability.index');
        Route::get('/loadDates','AvailabilityController@loadDates')->name('cruise.vendor.availability.loadDates');
        Route::match(['get','post'],'/store','AvailabilityController@store')->name('cruise.vendor.availability.store');
    });
});
