<?php

namespace App\Services;

use App\Jobs\SendMail;
use App\Models\PasswordResetToken;
use App\Repositories\PasswordResetTokenRepository;
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
    protected $passwordResetTokenRepository;

    /**
     * __construct
     *
     *
     */
    public function __construct(
        UserRepository $userRepository,
        PasswordResetTokenRepository $passwordResetTokenRepository
    ) {
        $this->userRepository               = $userRepository;
        $this->passwordResetTokenRepository = $passwordResetTokenRepository;
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
     * reset random password
     *
     * @param string $email
     *
     * @return [type]
     *
     */
    public function resetRandomPassword(string $email)
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
                $token = Str::uuid();
                $timeNow = Carbon::now();

                // upsear password_resets
                PasswordResetToken::updateOrInsert(
                    ['email'            => $user->email],
                    [
                        'token'         => $token,
                        'created_at'    => $timeNow
                    ]
                );

                // send mail
                SendMail::dispatch(
                    'mail.forgot-password',
                    [
                        'first_name'        => $user->first_name,
                        'last_name'         => $user->last_name,
                        'email_verified_at' => $timeNow->addMinutes(30),
                        'token'             => $token,
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

    /**
     * change password
     *
     * @param string $token
     * @param string $password
     * @return void
     */
    public function changePassword(string $token, string $password)
    {
        DB::beginTransaction();
        try {
            // check token forgot password
            $passwordResetToken = $this->passwordResetTokenRepository->findByToken($token);

            if (empty($passwordResetToken) || Carbon::parse($passwordResetToken->created_at)->addMinutes(30)->isPast()) {
                return 1;
            }

            // get user by email
            $user = $this->userRepository->findByEmail($passwordResetToken->email);

            // check data find user by email
            if ($user) {
                // update password
                $this->userRepository->updatePassword($user->email, $password);

                // send mail
                SendMail::dispatch(
                    'mail.reset-password',
                    [
                        'first_name'    => $user->first_name,
                        'last_name'     => $user->last_name,
                        'pass'          => $password
                    ],
                    $user->email,
                    "Đặt lại mật khẩu"
                );

                // commit transaction
                DB::commit();

                return 0;
            } else {
                // return page when email not exits
                return 2;
            }
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }
}
