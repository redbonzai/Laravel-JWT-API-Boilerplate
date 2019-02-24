<?php

namespace App\Services;

use App\Models\User;
use App\Traits\ApiResponder;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;


class AuthService
{
    use ApiResponder;

    public function __construct()
    {
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
    {
        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($request->password),
            ]);

            $token = JWTAuth::fromUser($user);
            //$payload = JWTAuth::setToken($token)->getPayload();

        } catch(JWTException $e) {
            return $this->errorResponse($e->getMessage(), 500);
        }

        return $this->respondWithToken($token, $user);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $credentials = $request->only(['email', 'password']);

        try {
            if (!$token = JWTAuth::attempt($credentials)) {
                return $this->errorResponse('Unauthorized', 401);
            }

           // $payload = JWTAuth::setToken($token)->getPayload();

            $user = $this->getAuthenticatedUser();

        } catch(JWTException $e) {
            return $this->errorResponse($e->getMessage(), 500);
        }

        return $this->respondWithToken($token, $user);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAuthenticatedUser()
    {
        try {

            if (!$user = auth()->user()) {
                return $this->errorResponse(['user_not_found'], 404);
            }

        } catch (Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {

            return $this->errorResponse(['token_expired'], $e->getStatusCode());

        } catch (Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {

            return $this->errorResponse(['token_invalid'], $e->getStatusCode());

        } catch (Tymon\JWTAuth\Exceptions\JWTException $e) {

            return $this->errorResponse(['token_absent'], $e->getStatusCode());

        }

        return $this->successResponse($user);
    }

    /**
     * @param $token
     * @param $user
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token, $user)
    {
        return response()->json([
            'user' => $user,
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }

}
