<?php

namespace App\Http\Controllers;

use App\Comments;
use App\Likes;
use Illuminate\Http\Request;

class CommentsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  integer  $post_id
     * @return \Illuminate\Http\Response
     */
    public function index(int $post_id)
    {
        return Comments::where('posts_id', $post_id)
                       ->orderBy('created_at', 'desc')
                       ->get();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \App\Comments
     */
    public function store(Request $request)
    {
        $request->validate([
            'post_id' => 'required|numeric',
            'content' => 'required|string'
        ]);

        if ($this->isConsecutiveComment($request->post_id)) {
            return json_encode([
                'success' => 'error', 
                'message' => 'Consecutive posts are not allowed.'
            ]);
        }

        if ($this->isSelfReply($request->post_id, $request->reply_to)) {
            return json_encode([
                'success' => 'error', 
                'message' => 'You can not reply to your own comment.'
            ]);
        }

        $comment = Comments::create([
            'user_id' => auth()->user()->id,
            'posts_id' => $request->post_id,
            'content' => $request->content,
            'reply_to' => $request->reply_to ?? 0
        ]);

        return $comment;
    }

    /*
     * Checks for consecutive posts
     * 
     * @param   integer  $post_id
     * @return  bool
     */
    private function isConsecutiveComment(int $post_id)
    {
        $comment = Comments::orderBy('created_at', 'desc')->first();
        return ($comment->user_id == auth()->user()->id) && ($comment->post_id == $post_id);
    }

    /*
     * Checks for self replies
     * 
     * @param   integer  $post_id
     * @param   integer  $reply_to
     * @return  bool
     */
    private function isSelfReply(int $post_id, int $reply_to = 0)
    {
        $comment = Comments::find('id', $reply_to);
        return ($comment->user_id == auth()->user()->id) && ($comment->post_id == $post_id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Comments  $comments
     * @return \App\Comments
     */
    public function update(Request $request, Comments $comments)
    {
        $request->validate([
            'content' => 'required|string'
        ]);

        $comments->content = $request->content;
        $comments->save();
        
        return $comments;
    }

    /**
     * Like or unlike the specified resource
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Comments  $comments
     * @return \App\Comments
     */
    public function like(Request $request, Comments $comments)
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
                'comments_id' => $comments->id
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
     * @param  integer  $post_id
     * @returm array    [id => [likes, dislikes]]
     */
    public function likes(int $post_id)
    {
        $comments = Comments::where('post_id', $post_id)->where('comments_id', ">", 0)->get();
        foreach ($comments as $comment) {
            $comment->likes = $post->likes()->where('comments_id', $comment->id)->where('dislike', 0)->count();
            $comment->dislikes = $post->likes()->where('comments_id', $comment->id)->where('dislike', 1)->count();
            ]
        }
        return ;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Comments  $comments
     * @return \Illuminate\Http\Response
     */
    public function destroy(Comments $comments)
    {
        $comments->destroy();
    }
}
