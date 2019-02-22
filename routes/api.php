<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Login and registration
Route::post('login', 'API\UserController@login');
Route::post('register', 'API\UserController@register');

// Public posts, comments, and likes
Route::get('/posts', 'PostsController@index');
Route::get('/post/{posts_id}', 'PostsController@show');
Route::get('/comments/{post_id}', 'CommentsController@index');
Route::get('likes/{posts_id}', 'PostsController@likes');


Route::group(['middleware' => 'auth:api'], function() {
    
    // User details
    Route::post('details', 'API\UserController@details');

    // Posts
    Route::post('post', 'PostsController@store');
    Route::post('post/{posts_id}', 'PostsController@update');
    Route::delete('post/{posts_id}', 'PostsController@destroy');

    // Comments
    Route::post('comment', 'CommentsController@store');
    Route::post('comment/{comments_id}', 'CommentsController@store');
    Route::delete('comment/{comments_id}', 'CommentsController@destroy');

    // Likes
    Route::post('/like/post/{posts_id}', 'PostsController@like');
    Route::post('/like/comment/{comments_id}', 'CommentsController@like');
});