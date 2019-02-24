<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\PostResource;
use App\Services\AuthService;
use App\Traits\ApiResponder;
use Illuminate\Http\Request;
use App\Models\Likes;
use App\Models\Posts;
use Illuminate\Support\Facades\Log;

class PostsController extends Controller
{
    use ApiResponder;

    protected $auth;

    public function __construct(AuthService $auth)
    {
        //
    }

    /**
     * Display paginated posts
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        //return Posts::orderBy('created_at', 'desc')->get();
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
            PostResource::collection(Posts::with('user')->paginate(25))
        );
    }

    /**
     * @param Posts $post
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Posts $post)
    {
        return $this->successResponse(new PostResource($post));
    }

    /**
     * Store a Post
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|between:3,128',
            'content' => 'required|string'
        ]);

        $post = Posts::create([
            'user_id' => auth()->user()->id,
            'title' => $request->get('title'),
            'content' => $request->get('content'),
            'published' => $request->get('published')
        ]);
        
        return $this->successResponse(new PostResource($post));
    }

    /**
     * @param Request $request
     * @param Posts $post
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, Posts $post)
    {
        $request->validate([
        'title' => 'required|string|between:8,128',
        'content' => 'required|string'
    ]);

        $update = $post->update([
            'title' => $request->get('title'),
            'content' => $request->get('content'),
            'published' => $request->get('published')
        ]);
        
        return $this->successResponse('post updated');
    }

    /**
     * Like or unlike the specified Post
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function like(Request $request)
    {
        // Validate data
        $request->validate([
            'posts_id' => 'required|numeric',
            'dislike' => 'required|numeric'
        ]);

        // Update or create
        $like = Likes::updateOrCreate([
                'user_id' => auth()->user()->id,
                'posts_id' => $request->posts_id,
                'comments_id' => null,
                'dislike' => $request->dislike
         ]);
        
        return $this->successResponse($like);
    }

    /**
     * @param $postId
     * @return \Illuminate\Http\JsonResponse
     */
    public function likes($postId)
    {
        return $this->successResponse([
            "likes" => Likes::where('posts_id', '=', $postId)->where('dislike', '=', 0)->count(),
            "dislikes" => Likes::where('posts_id', '=', $postId)->where('dislike', '=', 1)->count()
        ]);
    }

    /**
     * Destroy a specific post
     * @param $postId
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function destroy($postId)
    {
        /** @var Posts $comment */
        $post = Posts::findOrFail($postId);
        $post->delete();

        return $this->successResponse($post);
    }
}
