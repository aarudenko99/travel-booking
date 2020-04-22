<?php
use \Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'inbox', 'middleware' => ['auth', 'verified']], function () {
	Route::post('/send', 'InboxController@send')->middleware('throttle:60,1')->name('inbox.send');
	Route::post('/init', 'InboxController@initChat')->middleware('throttle:60,1')->name('inbox.init');
	Route::post('/notifications', 'InboxController@notifications')->name('inbox.notifications');
	Route::post('/reload', 'InboxController@reload')->middleware('throttle:60,1')->name('inbox.reload');
	Route::post('/read', 'InboxController@markRead')->middleware('throttle:60,1')->name('inbox.read');
});
