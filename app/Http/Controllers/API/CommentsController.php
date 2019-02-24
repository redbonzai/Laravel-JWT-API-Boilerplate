<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\CommentResource;
use App\Models\Comments;
use App\Models\Likes;
use App\Models\Posts;
use App\Traits\ApiResponder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CommentsController extends Controller
{
    use ApiResponder;

    /**
     * Display all comments
     * @return \Illuminate\Http\JsonResponse
     */
    public function index ()
    {
        try {
            $user = auth()->user();

        } catch (\Tymon\JWTAuth\Exceptions\UserNotDefinedException $e) {
            Log::error('cannot verify user authentication', [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'code' => $e->getCode(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);

            return $this->errorResponse($e->getTraceAsString(), 401);
        }

        return $this->successResponse(
            CommentResource::collection(Comments::with('user')->paginate(25))
        );
    }

    /**
     * Display a specific comment
     * @param Comments $comment
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Comments $comment)
    {
        return $this->successResponse(new CommentResource($comment));
    }

    /**
     * Get all comments from a given post ID
     * @param $post
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCommentsByPostId($post)
    {
        $comments = Comments::where('posts_id', '=', $post)->get();
        return $this->successResponse($comments);

    }

    /**
     * Store a new comment on a post
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'posts_id' => 'required|numeric',
            'content' => 'required|string'
        ]);

        if ($this->isConsecutiveComment($request->posts_id)) {
            return $this->errorResponse('Consecutive posts are not allowed', 401);
        }

        if ($this->isSelfReply($request->posts_id, $request->reply_to)) {
            return $this->errorResponse('You can not reply to your own comment.', 401);
        }

        $comment = Comments::create([
            'user_id' => auth()->user()->id,
            'posts_id' => $request->get('posts_id'),
            'content' => $request->get('content'),
            'reply_to' => $request->get('reply_to') ?? 0
        ]);

        return $this->successResponse(new CommentResource($comment));
    }

    /**
     * Checks for consecutive posts
     * @param int $post_id
     * @return bool
     */
    private function isConsecutiveComment(int $post_id)
    {
        $comment = Comments::orderBy('created_at', 'desc')->first();
        $consecutive = $comment
            ? ($comment->user_id == auth()->user()->id) && ($comment->post_id == $post_id)
            : false;

        return $consecutive;
    }

    /**
     * Checks for self replies
     * @param int $post_id
     * @param int $reply_to
     * @return bool
     */
    private function isSelfReply(int $post_id, int $reply_to = 0)
    {
        $comment = Comments::find($reply_to);
        $selfReply = $comment
            ? ($comment->user_id == auth()->user()->id) && ($comment->post_id == $post_id)
            : false;
        return $selfReply;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Comments $comment
     * @return \App\Models\Comments
     */
    public function update(Request $request, Comments $comment)
    {
        $request->validate([
            'content' => 'required|string'
        ]);

        $comment->content = $request->content;
        $comment->save();
        
        return $comment;
    }

    /**
     * Like or unlike the specified resource
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Comments  $comments
     * @return \App\Models\Comments
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
                'user_id' => auth()->user()->id,
                'posts_id' => $request->post_id,
                'comments_id' => $comments->id
        ],
            [
                'unlike' => $request->unlike
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

        }
        return ;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Comments  $comments
     * @return \Illuminate\Http\Response
     */
    public function destroy(Comments $comments)
    {
        $comments->destroy($comments);
    }
}
