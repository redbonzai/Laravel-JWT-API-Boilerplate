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

Route::get('/', 'PublicController@index');
Route::get("/register", "PublicController@register");
Route::get('/post/{posts_id}', 'PublicController@post');
Route::get('/post/create', 'PublicController@create');