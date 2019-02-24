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
    $this->get('/likes/post/{posts_id}', 'API\PostsController@likes');
    $this->get('/likes/comment/{posts_id}', 'API\CommentsController@likes');
    $this->post('/like/post/{posts_id}', 'API\PostsController@like');
    $this->post('/like/comment/{comment_id}', 'API\CommentsController@like');
    $this->resource('/comments', 'API\CommentsController');
    $this->get('comments/posts/{posts_id}', 'API\CommentsController@getCommentsByPostId');

});
