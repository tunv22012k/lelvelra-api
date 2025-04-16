<?php

namespace App\Http\Controllers\User;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\ChangePasswordRequest;
use App\Http\Requests\User\ForgotPasswordRequest;
use App\Http\Requests\User\RegisterUserRequest;
use App\Http\Requests\User\ResetRandomPasswordRequest;
use App\Http\Resources\UserCollection;
use App\Http\Resources\UserResource;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Throwable;

/**
 * UserController
 */
class UserController extends Controller
{
    protected $userService;

    /**
     * __construct
     *
     * @param UserService $userService
     */
    public function __construct(UserService $userService)
    {
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
        return ApiResponse::success(new UserResource(Auth::user()));
    }

    /**
     * list user
     *
     * @return response
     */
    public function listUser(Request $request)
    {
        return ApiResponse::success(new UserCollection($this->userService->listUser($request)));
    }

    /**
     * reset random password
     *
     * @param ResetRandomPasswordRequest $request
     * @return void
     */
    public function resetRandomPassword(ResetRandomPasswordRequest $request)
    {
        try {
            // call service reset password
            $update = $this->userService->resetRandomPassword($request['email']);

            // check data
            if ($update) {
                return ApiResponse::success(null, __('messages.reset_password_success'));
            } else {
                return ApiResponse::validation(__('messages.user_not_exits'));
            }
        } catch (\Throwable $ex) {
            Log::error($ex);
            return ApiResponse::error(__('messages.error_bug'));
        }
    }

    /**
     * forgot password
     *
     * @param ForgotPasswordRequest $request
     * @return void
     */
    public function forgotPassword(ForgotPasswordRequest $request)
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

    /**
     * change password
     *
     * @param ChangePasswordRequest $request
     * @return void
     */
    public function changePassword(ChangePasswordRequest $request)
    {
        try {
            // call service change password
            $update = $this->userService->changePassword($request['token'], $request['password']);

            // check data
            if ($update == 0) {
                return ApiResponse::success(null, __('messages.change_password_success'));
            } else if ($update == 1) {
                return ApiResponse::validation(__('messages.change_password_fail'));
            } else if ($update == 2) {
                return ApiResponse::validation(__('messages.user_not_exits'));
            }
        } catch (\Throwable $ex) {
            Log::error($ex);
            return ApiResponse::error(__('messages.error_bug'));
        }
    }
}
