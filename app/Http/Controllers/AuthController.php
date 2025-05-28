<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponse;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\RefreshToken;
use App\Repositories\RefreshTokenRepository;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Tymon\JWTAuth\Facades\JWTAuth;

/**
 * AuthController
 */
class AuthController extends Controller
{
    protected $userRepository;
    protected $refreshTokenRepository;

    /**
     * __construct
     *
     * @param UserRepository $userRepository
     * @param RefreshTokenRepository $refreshTokenRepository
     */
    public function __construct(UserRepository $userRepository, RefreshTokenRepository $refreshTokenRepository)
    {
        $this->userRepository = $userRepository;
        $this->refreshTokenRepository = $refreshTokenRepository;
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

        $user = $this->userRepository->findByEmail($request->email);

        // check user exit
        if (empty($user)) {
            return ApiResponse::unauthorized(__('auth.login.acc_not_exits'));
        }

        // check user active
        if (empty($user->active_flg)) {
            return ApiResponse::forbidden(__('messages.acc_not_active'));
        }

        // check user login
        if (!$token = JWTAuth::attempt($credentials)) {
            return ApiResponse::unauthorized(__('messages.email_or_password_incorrect'));
        }

        // create refresh token
        $refreshToken = Str::random(60);
        $this->refreshTokenRepository->create([
            'user_id'       => $user->id,
            'token'         => $refreshToken,
            'expires_at'    => now()->addDays(30)
        ]);

        return ApiResponse::success([
            'access_token'  => $token,
            'refresh_token' => $refreshToken,
            'token_type'    => 'bearer',
            'expires_in'    => Auth::guard('api')->factory()->getTTL() * 60,
        ], __('messages.login_susscess'));
    }

    /**
     * refresh token
     *
     * @param RefreshToken $request
     * @return void
     */
    public function refreshToken(RefreshToken $request)
    {
        $refreshTokenInput = $request->refresh_token;

        // find token refresh
        $refreshTokenModel = $this->refreshTokenRepository->findByToken($refreshTokenInput);

        if (!$refreshTokenModel) {
            return ApiResponse::unauthorized(__('messages.invalid_refresh_token'));
        }

        // get user token refresh
        $user = $refreshTokenModel->user;

        // create new access token
        $newToken = JWTAuth::fromUser($user);

        return ApiResponse::success([
            'access_token'  => $newToken,
            'token_type'    => 'bearer',
            'expires_in'    => Auth::guard('api')->factory()->getTTL() * 60,
        ], __('messages.token_refreshed'));
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
