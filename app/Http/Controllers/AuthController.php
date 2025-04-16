<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponse;
use App\Http\Requests\LoginRequest;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;

/**
 * AuthController
 */
class AuthController extends Controller
{
    protected $userRepository;

    /**
     * __construct
     *
     */
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * login
     *
     * @param LoginRequest $request
     * @return void
     */
    public function login(LoginRequest $request)
    {
        $credentials = $request->only('email', 'password');

        // check user active
        $user = $this->userRepository->findByEmail($request->email);
        if (empty($user->active_flg)) {
            return ApiResponse::forbidden(__('messages.acc_not_active'));
        }

        // check user login
        if (!$token = JWTAuth::attempt($credentials)) {
            return ApiResponse::unauthorized(__('messages.email_or_password_incorrect'));
        }

        return ApiResponse::success([
            'access_token'  => $token,
            'token_type'    => 'bearer',
            'expires_in'    => Auth::guard('api')->factory()->getTTL() * 60,
        ], __('messages.login_susscess'));
    }

    /**
     * refresh token
     *
     * @return void
     */
    public function refreshToken()
    {
        try {
            $newToken = JWTAuth::parseToken()->refresh();

            return ApiResponse::success([
                'access_token'  => $newToken,
                'token_type'    => 'bearer',
                'expires_in'    => Auth::guard('api')->factory()->getTTL() * 60,
            ]);
        } catch (\Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
            return response()->json(['message' => 'Refresh token không hợp lệ'], 401);
        }
    }

    /**
     * logout
     *
     * @return response
     */
    public function logout()
    {
        Auth::logout();
        return ApiResponse::success(null, __('messages.logout_sucess'));
    }
}
