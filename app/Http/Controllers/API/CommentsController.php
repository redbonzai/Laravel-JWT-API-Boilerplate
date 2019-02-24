<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\CommentResource;
use App\Models\Comments;
use App\Models\Likes;
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
     * Update a specific comment
     * @param Request $request
     * @param Comments $comment
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, Comments $comment)
    {
        $request->validate([
            'content' => 'required|string'
        ]);

        $update = $comment->update([
            'content' => $request->get('content')
        ]);
        
        return $this->successResponse(['comment updated' =>$update]);
    }

    /**
     * Like or dislike a specified comment
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function like(Request $request)
    {
        // Validate data
        $request->validate([
            'comments_id' => 'required|numeric',
            'dislike' => 'required|numeric'
        ]);

        // Update or create
        $like = Likes::updateOrCreate([
                'user_id' => auth()->user()->id,
                'posts_id' => null,
                'comments_id' => $request->comments_id,
                'dislike' => $request->dislike
        ]);

        return $this->successResponse($like);
    }

    /**
     * @param $commentId
     * @return \Illuminate\Http\JsonResponse
     */
    public function likes($commentId)
    {
        return $this->successResponse([
            "likes" => Likes::where('comments_id', '=', $commentId)->where('dislike', '=', 0)->count(),
            "dislikes" => Likes::where('comments_id', '=', $commentId)->where('dislike', '=', 1)->count()
        ]);
    }

    /**
     * @param $commentId
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function destroy($commentId)
    {
        /** @var Comments $comment */
        $comment = Comments::findOrFail($commentId);
        $comment->delete();

        return $this->successResponse($comment);
    }
}
