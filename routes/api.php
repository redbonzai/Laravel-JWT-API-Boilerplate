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
$this->group(['prefix' => 'auth'], function () {
    $this->post('/register', 'API\UserController@register');
    $this->post('/login', 'API\UserController@login');
});

$this->group(['prefix' => 'blog', 'middleware' => ['jwt.verify']], function () {
    $this->resource('/posts', 'API\PostsController');
    $this->get('/likes/{posts_id}', 'API\PostsController@likes');
    $this->post('/like/{posts_id}', 'API\PostsController@like');

    $this->get('/comments/{post_id}', 'CommentsController@index');
});
// Public posts, comments, and likes



/*$this->group(['middleware' => 'auth:api'], function() {
    
    // User details
    $this->post('details', 'API\UserController@details');

    // Posts
    $this->post('post', 'PostsController@store');
    $this->post('post/{posts_id}', 'PostsController@update');
    $this->delete('post/{posts_id}', 'PostsController@destroy');

    // Comments
    $this->post('comment', 'CommentsController@store');
    $this->post('comment/{comments_id}', 'CommentsController@store');
    $this->delete('comment/{comments_id}', 'CommentsController@destroy');

    // Likes
    $this->post('/like/post/{posts_id}', 'PostsController@like');
    $this->post('/like/comment/{comments_id}', 'CommentsController@like');
});*/
