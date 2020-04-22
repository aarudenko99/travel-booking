<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 7/1/2019
 * Time: 10:02 AM
 */
Route::prefix('hotel')->group(function() {
    Route::get('/', 'Admin\HotelController@index');
});
