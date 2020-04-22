<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 7/16/2019
 * Time: 3:52 PM
 */
Route::get('/', 'HomeController@index');

Route::get('/home', 'HomeController@index')->name('home');
Route::get('/a', 'Controller@a')->name('home2');
Route::get('/update', 'HomeController@updateMigrate');

//Login
Auth::routes(['verify' => true]);
//Custom User Login and Register
Route::post('register', '\Modules\User\Controllers\UserController@userRegister')->name('auth.register');
Route::post('login', '\Modules\User\Controllers\UserController@userLogin')->name('auth.login');
Route::post('logout', '\Modules\User\Controllers\UserController@logout')->name('auth.logout');
// Social Login
Route::get('social-login/{provider}', 'Auth\LoginController@socialLogin');
Route::get('social-callback/{provider}', 'Auth\LoginController@socialCallBack');

//Contact
Route::match(['get', 'post'], '/contact', '\Modules\Contact\Controllers\ContactController@index'); // Contact

// Tour
Route::group(['prefix' => config('tour.tour_route_prefix')], function () {
	Route::get('/', '\Modules\Tour\Controllers\TourController@index')->name('tour.search'); // Search
	Route::get('/{slug}', '\Modules\Tour\Controllers\TourController@detail'); // Detail
});

// Cruise
Route::group(['prefix' => config('cruise.cruise_route_prefix')], function () {
	Route::get('/', '\Modules\Cruise\Controllers\CruiseController@index')->name('cruise.search'); // Search
	Route::get('/{slug}', '\Modules\Cruise\Controllers\CruiseController@detail'); // Detail
});

// News
Route::group(['prefix' => config('news.news_route_prefix')], function () {
	Route::get('/' . config('news.news_category_route_prefix') . '/{slug}', '\Modules\News\Controllers\CategoryNewsController@index');
	Route::get('/' . config('news.news_tag_route_prefix') . '/{slug}', '\Modules\News\Controllers\TagNewsController@index');
	Route::get('/', '\Modules\News\Controllers\NewsController@index'); // News Page
	Route::get('/{slug}', '\Modules\News\Controllers\NewsController@detail'); // Detail
});

// Booking
Route::group(['prefix' => config('booking.booking_route_prefix')], function () {
	Route::post('/addToCart', '\Modules\Booking\Controllers\BookingController@addToCart')->middleware(['auth', 'verified']); // Detail
	Route::post('/doCheckout', '\Modules\Booking\Controllers\BookingController@doCheckout')->middleware(['auth', 'verified']); // Detail

	Route::get('/confirm/{gateway}', '\Modules\Booking\Controllers\BookingController@confirmPayment'); // Detail
	Route::get('/cancel/{gateway}', '\Modules\Booking\Controllers\BookingController@cancelPayment'); // Detail

	Route::get('/{code}', '\Modules\Booking\Controllers\BookingController@detail')->middleware(['auth', 'verified']); // Detail
	Route::get('/{code}/checkout', '\Modules\Booking\Controllers\BookingController@checkout')->middleware(['auth', 'verified']); // Detail
	Route::get('/{code}/check-status', '\Modules\Booking\Controllers\BookingController@checkStatusCheckout')->middleware(['auth', 'verified']); // Detail
});

//Review
Route::group(['middleware' => ['auth', 'verified']], function () {
	Route::get('/review', function () {return redirect('/');});
	Route::post('/review', '\Modules\Review\Controllers\ReviewController@addReview');
});

// Media
Route::group(['prefix' => 'media'], function () {
	Route::get('/preview/{id}/{size?}', '\Modules\Media\Controllers\MediaController@preview'); //
});
Route::group(['middleware' => ['auth', 'verified']], function () {
	Route::match(['get', 'post'], '/admin/module/media/store', '\Modules\Media\Admin\MediaController@store');
	Route::match(['get', 'post'], '/admin/module/media/getLists', '\Modules\Media\Admin\MediaController@getLists');
});

Route::post('newsletter/subscribe', '\Modules\User\Controllers\UserController@subscribe')->name('newsletter.subscribe');
//Contact
Route::match(['get', 'post'], '/contact', '\Modules\Contact\Controllers\ContactController@index'); // Contact
Route::match(['post'], '/contact/store', '\Modules\Contact\Controllers\ContactController@store'); // Contact
