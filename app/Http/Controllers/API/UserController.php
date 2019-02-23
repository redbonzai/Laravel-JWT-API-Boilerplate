<?php

namespace App\Http\Controllers\API;

use App\Services\AuthService;
use App\Traits\ApiResponder;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class UserController extends Controller
{
    use ApiResponder;

    public $successStatus = 200;

    /** @var AuthService $auth */
    protected $auth;

    public function __construct(AuthService $auth)
    {
        $this->auth = $auth;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $response = $this->auth->login($request);

       // $this->recordJwtToken($response);
        return $this->successResponse(
            $this->auth->login($request)
        );
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
    {
        // Validate registration details
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|between:3,100',
            'email' => 'required|email|unique:users',
            'password' => 'required|between:8,12',
            'repeat' => 'required|same:password',
        ]);

        // Validation fails
        if ($validator->fails()) {
            return $this->errorResponse(
                $validator->errors()->getMessages(),
                401
            );
        }

        return $this->successResponse(
            $this->auth->register($request)
        );
    }

    /**
     * details api
     *
     * @return \Illuminate\Http\Response
     */
    public function details()
    {
        $user = Auth::user();
        return response()->json(['success' => $user], $this->successStatus);
    }
}
