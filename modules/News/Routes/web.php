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

Route::group(['prefix'=>config('news.news_route_prefix')],function(){
    Route::get('/'.config('news.news_category_route_prefix').'/{slug}', 'CategoryNewsController@index');
    Route::get('/'.config('news.news_tag_route_prefix').'/{slug}', 'TagNewsController@index');
    Route::get('/','NewsController@index');// News Page
    Route::get('/{slug}','NewsController@detail');// Detail
});

Route::group(['prefix'=>app()->getLocale()],function(){
    Route::group(['prefix'=>config('news.news_route_prefix')],function(){
        Route::get('/'.config('news.news_category_route_prefix').'/{slug}', 'CategoryNewsController@index')->name('news.category.detail');
        Route::get('/'.config('news.news_tag_route_prefix').'/{slug}', 'TagNewsController@index')->name('news.tag.detail');
        Route::get('/','NewsController@index')->name('news.index');// News Page
        Route::get('/{slug}','NewsController@detail')->name('news.detail');// Detail
    });
});