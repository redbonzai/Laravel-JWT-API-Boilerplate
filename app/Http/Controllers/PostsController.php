<?php

namespace App\Http\Controllers;

use App\Posts;
use App\Likes;
use Illuminate\Http\Request;

class PostsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Posts::orderBy('created_at', 'desc')->get();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Posts  $posts
     * @return \Illuminate\Http\Response
     */
    public function show(Posts $posts)
    {
        return $posts;
    }

        /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \App\Posts
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|between:3,128',
            'content' => 'required|string'
        ]);

        $post = Posts::create([
            'user_id' => auth()->user()->id,
            'title' => $request->title,
            'content' => $request->content,
        ]);
        
        return $post;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Posts $posts
     * @return \App\Posts
     */
    public function update(Request $request, Posts $post)
    {
        // Validate the post data
        $request->validate([
            'title' => 'required|string|between:8,128',
            'content' => 'required|string'
        ]);

        // Update a post
        $post->title = $request->title;
        $post->content = $request->content;
        $post->save();
        
        return $post;
    }

    /**
     * Like or unlike the specified resource
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Posts $post
     * @return \App\Posts
     */
    public function like(Request $request, Posts $post)
    {
        // Validate data
        $request->validate([
            'post_id' => 'required|numeric',
            'unlike' => 'required|numeric'
        ]);

        // Update or create
        $like = Likes::updateOrCreate([
            [
                'user_id' => auth()->user()->id,
                'posts_id' => $request->post_id,
                'comments_id' => 0
            ],
            [
                'unlike' => $request->unlike
            ]
        ]);
        
        return $like;
    }

    /**
     * Get likes for a post
     * 
     * @param Posts $post
     * @return array [likes, dislikes]]
     */
    public function likes(Posts $post)
    {
        return [
            "likes" => $post->likes()->where('comments_id', 0)->where('dislike', 0)->count(),
            "dislikes" => $post->likes()->where('comments_id', 0)->where('dislike', 1)->count()
        ];
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Posts $post
     * @return \Illuminate\Http\Response
     */
    public function destroy(Posts $post)
    {
        $post->destroy();
    }
}
