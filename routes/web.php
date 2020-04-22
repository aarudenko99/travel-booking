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
Route::get('/intro', 'LandingpageController@index');
Route::get('/', 'HomeController@index');
Route::post('/install/check-db', 'HomeController@checkConnectDatabase');
Route::get('/home', 'HomeController@index')->name('home');
Route::get('/a', 'Controller@a')->name('home2');
// Route::get('/testtest','HomeController@testtest');
Route::get('/contact', 'HomeController@getContact');
Route::post('/contact', 'HomeController@postContact');

//Clear configurations:
Route::get('/en/config-clear', function() {
	$status = Artisan::call('config:clear');
	return '<h1>Configurations cleared</h1>';
});

//Clear cache:
Route::get('/en/cache-clear', function() {
	$status = Artisan::call('cache:clear');
	return '<h1>Cache cleared</h1>';
});

//Login
Auth::routes(['verify' => true]);
//Custom User Login and Register
Route::post('register', '\Modules\User\Controllers\UserController@userRegister')->name('auth.register');
Route::post('login', '\Modules\User\Controllers\UserController@userLogin')->name('auth.login');
Route::post('logout', '\Modules\User\Controllers\UserController@logout')->name('auth.logout');

//Agent
Route::post('agent_id_push', '\Modules\User\Controllers\UserController@pushAgentId')->name('auth.agentId'); //agent
Route::post('agent_id_confirm', '\Modules\User\Controllers\UserController@confirmAgentId')->name('auth.agentIdConfirm'); //agent_id verify
Route::post('/agent_change', '\Modules\User\Controllers\UserController@agent')->name('auth.agentChange');

// Social Login
Route::get('social-login/{provider}', 'Auth\LoginController@socialLogin');
Route::get('social-callback/{provider}', 'Auth\LoginController@socialCallBack');

//Contact
Route::match(['get', 'post'], '/contact', '\Modules\Contact\Controllers\ContactController@index'); // Contact
Route::match(['post'], '/contact/store', '\Modules\Contact\Controllers\ContactController@store'); // Contact

Route::get('/test_functions', 'HomeController@test');
Route::get('/update', 'HomeController@updateMigrate');

//Homepage
Route::post('newsletter/subscribe', '\Modules\User\Controllers\UserController@subscribe')->name('newsletter.subscribe');

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
	Route::get('/' . config('news.news_category_route_prefix') . '/{slug}', '\Modules\News\Controllers\CategoryNewsController@index')->name('news.category.detail');
	Route::get('/' . config('news.news_tag_route_prefix') . '/{slug}', '\Modules\News\Controllers\TagNewsController@index')->name('news.tag.detail');
	Route::get('/', '\Modules\News\Controllers\NewsController@index')->name('news.index'); // News Page
	Route::get('/{slug}', '\Modules\News\Controllers\NewsController@detail')->name('news.detail'); // Detail
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

// Media
Route::group(['prefix' => 'media'], function () {
	Route::get('/preview/{id}/{size?}', '\Modules\Media\Controllers\MediaController@preview'); //
});
Route::group(['middleware' => ['auth', 'verified']], function () {
	Route::match(['get', 'post'], '/admin/module/media/store', '\Modules\Media\Admin\MediaController@store');
	Route::match(['get', 'post'], '/admin/module/media/getLists', '\Modules\Media\Admin\MediaController@getLists');
});

//Review
Route::group(['middleware' => ['auth', 'verified']], function () {
	Route::get('/review', function () {return redirect('/');});
	Route::post('/review', '\Modules\Review\Controllers\ReviewController@addReview');
});

// Logs
Route::get('admin/logs', '\Rap2hpoutre\LaravelLogViewer\LogViewerController@index')->middleware(['auth', 'dashboard', 'system_log_view']);

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
