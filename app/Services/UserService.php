<?php

namespace App\Services;

use App\Jobs\SendMail;
use App\Repositories\UserRepository;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

/**
 * UserService
 */
class UserService extends BaseService
{
    protected $userRepository;

    /**
     * __construct
     *
     *
     */
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * register user
     *
     * @param [type] $request
     * @return void
     */
    public function registerUser($request)
    {
        DB::beginTransaction();
        try {
            // create user
            $user = $this->userRepository->create([
                'first_name'    => $request->first_name,
                'last_name'     => $request->last_name,
                'sex'           => $request->sex,
                'role'          => $request->role,
                'email'         => $request->email,
                'password'      => bcrypt($request->password),
            ]);

            // send mail
            SendMail::dispatch(
                'mail.register-user',
                [
                    'first_name'    => $request->first_name,
                    'last_name'     => $request->last_name,
                    'email'         => $request->email,
                    'sex'           => $request->sex,
                    'role'          => $request->role,
                    'password'      => $request->password,
                ],
                $request->email,
                "Tạo mới người dùng"
            );

            // commit transaction
            DB::commit();

            return $user;
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }

    /**
     * active user register
     *
     * @param integer $userId
     * @return void
     */
    public function activeUserRegister(int $userId)
    {
        $this->userRepository->update($userId, [
            'active_flg'    => true
        ]);
    }

    /**
     * reset password
     *
     * @param string $email
     *
     * @return [type]
     *
     */
    public function resetPassword(string $email)
    {
        DB::beginTransaction();
        try {
            // get user by email
            $user = $this->userRepository->findByEmail($email);

            // check data find user by email
            if ($user) {
                // radom password
                $randomPass = Str::lower(Str::random(10));

                // update password
                $this->userRepository->updatePassword($email, $randomPass);

                // send mail
                SendMail::dispatch(
                    'mail.reset-password',
                    [
                        'first_name'    => $user->first_name,
                        'last_name'     => $user->last_name,
                        'pass'          => $randomPass
                    ],
                    $email,
                    "Đặt lại mật khẩu"
                );

                // commit transaction
                DB::commit();

                return true;
            } else {
                // return page when email not exits
                return false;
            }
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }

    /**
     * forgot password
     *
     * @param string $email
     *
     * @return [type]
     *
     */
    public function forgotPassword(string $email)
    {
        DB::beginTransaction();
        try {
            // get user by email
            $user = $this->userRepository->findByEmail($email);

            // check data find user by email
            if ($user) {
                $timeChangePassword = Carbon::now()->addMinutes(30);

                // set time change password
                $this->userRepository->update($user->id, [
                    'email_verified_at' => $timeChangePassword
                ]);

                // send mail
                SendMail::dispatch(
                    'mail.forgot-password',
                    [
                        'first_name'        => $user->first_name,
                        'last_name'         => $user->last_name,
                        'email_verified_at' => $timeChangePassword
                    ],
                    $email,
                    "Quên mật khẩu"
                );

                // commit transaction
                DB::commit();

                return true;
            } else {
                // return page when email not exits
                return false;
            }
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }
}
