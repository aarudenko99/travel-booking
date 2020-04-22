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
Route::group(['prefix'=>'user/tour'],function(){
    Route::match(['get',],'/','ManageTourController@manageTour')->name('tour.vendor.list');
    Route::match(['get',],'/create','ManageTourController@createTour')->name('tour.vendor.create');
    Route::match(['get',],'/edit/{slug}','ManageTourController@editTour')->name('tour.vendor.edit');
    Route::match(['get','post'],'/del/{slug}','ManageTourController@deleteTour')->name('tour.vendor.delete');
    Route::match(['post'],'/store/{slug}','ManageTourController@store')->name('tour.vendor.store');
    Route::get('/booking-report','ManageTourController@bookingReport')->name("tour.vendor.booking_report");

    Route::group(['prefix'=>'availability'],function(){
        Route::get('/','AvailabilityController@index')->name('tour.vendor.availability.index');
        Route::get('/loadDates','AvailabilityController@loadDates')->name('tour.vendor.availability.loadDates');
        Route::match(['get','post'],'/store','AvailabilityController@store')->name('tour.vendor.availability.store');
    });
});
