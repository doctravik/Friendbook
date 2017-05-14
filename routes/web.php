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

Auth::routes();

Route::get('/', 'WelcomeController@index');
Route::get('/home', 'HomeController@index');
Route::get('/{user}', 'ProfileController@index');

Route::get('/friends/requests/sent', 'FriendRequestController@indexRequestsSent');
Route::get('/friends/requests/received', 'FriendRequestController@indexRequestsReceived');
Route::post('/friends/requests/{user}', 'FriendRequestController@store');
Route::delete('/friends/requests/{user}', 'FriendRequestController@destroy');

Route::get('/{user}/friends', 'FriendController@index');
Route::post('/friends/{user}', 'FriendController@store');
Route::delete('/friends/{user}', 'FriendController@destroy');

Route::get('/{user}/following', 'FollowingController@index');
Route::get('/{user}/followers', 'FollowController@index');
Route::post('/follow/{user}', 'FollowController@store');
Route::delete('/unfollow/{user}', 'FollowController@destroy');