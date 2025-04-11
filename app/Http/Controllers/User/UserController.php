<?php

namespace App\Http\Controllers\User;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterUserRequest;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Throwable;

/**
 * UserController
 */
class UserController extends Controller
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
     * create user
     *
     * @param RegisterUserRequest $request
     * @return response
     */
    public function register(RegisterUserRequest $request)
    {
        try {
            $user = $this->userRepository->create([
                'first_name'    => $request->first_name,
                'last_name'     => $request->last_name,
                'sex'           => $request->sex,
                'role'          => $request->role,
                'email'         => $request->email,
                'password'      => bcrypt($request->password),
            ]);

            return ApiResponse::success($user, __('messages.register_user_success'));
        } catch (Throwable $ex) {
            Log::error($ex);
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
}
