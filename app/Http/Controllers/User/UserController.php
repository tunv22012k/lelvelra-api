<?php

namespace App\Http\Controllers\User;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\RegisterUserRequest;
use App\Repositories\UserRepository;
use App\Services\UserService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Request;
use Throwable;

/**
 * UserController
 */
class UserController extends Controller
{
    protected $userRepository;
    protected $userService;

    /**
     * __construct
     *
     */
    public function __construct(UserRepository $userRepository, UserService $userService)
    {
        $this->userRepository   = $userRepository;
        $this->userService      = $userService;
    }

    /**
     * create user
     *
     * @param RegisterUserRequest $request
     * @return response
     */
    public function register(RegisterUserRequest $request)
    {
        try {
            // create user
            $user = $this->userService->registerUser($request);

            return ApiResponse::success($user, __('messages.register_user_success'));
        } catch (Throwable $ex) {
            Log::error($ex);
            return ApiResponse::error(__('messages.error_bug'));
        }
    }

    /**
     * active user register
     *
     * @param int $userId
     * @return void
     */
    public function activeUserRegister(int $userId)
    {
        try {
            // active user
            $this->userService->activeUserRegister($userId);

            return ApiResponse::success(null, __('messages.active_user_success'));
        } catch (Throwable $ex) {
            Log::error($ex);
            return ApiResponse::error(__('messages.error_bug'));
        }
    }

    /**
     * get info user login
     *
     * @return response
     */
    public function infoUser()
    {
        return ApiResponse::success(Auth::user());
    }

    /**
     * reset password
     *
     * @param Request $request
     * @return void
     */
    public function resetPassword(Request $request)
    {
        try {
            // call service reset password
            $update = $this->userService->resetPassword($request['email']);

            // check data
            if ($update) {
                return ApiResponse::success(null, __('messages.reset_password_success'));
            } else {
                return ApiResponse::validation(__('messages.reset_password_fail'));
            }
        } catch (\Throwable $ex) {
            Log::error($ex);
            return ApiResponse::error(__('messages.error_bug'));
        }
    }

    /**
     * forgot password
     *
     * @param Request $request
     * @return void
     */
    public function forgotPassword(Request $request)
    {
        try {
            // call service forgot password
            $update = $this->userService->forgotPassword($request['email']);

            // check data
            if ($update) {
                return ApiResponse::success(null, __('messages.forgot_password_success'));
            } else {
                return ApiResponse::validation(__('messages.forgot_password_fail'));
            }
        } catch (\Throwable $ex) {
            Log::error($ex);
            return ApiResponse::error(__('messages.error_bug'));
        }
    }
}
