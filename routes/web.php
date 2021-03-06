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

// Route::get('queue/{text}', 'Api\TestQueueController@test');

Route::get('/franceconnect/login-authorize', 'Auth\FranceConnectController@oauthLoginAuthorize');
Route::get('/franceconnect/login-callback', 'Auth\FranceConnectController@oauthLoginCallback');
Route::get('/franceconnect/test', 'Auth\FranceConnectController@test');


Route::get('api/api-engagement/flux', 'Api\EngagementController@feed');
Route::get('/{any}', 'PagesController@spa')->where('any', '.*');
