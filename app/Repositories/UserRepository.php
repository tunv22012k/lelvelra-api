<?php

namespace App\Repositories;

use App\Models\User;
use App\Repositories\BaseRepository;
use Illuminate\Support\Facades\Hash;

/**
 * UserRepository
 */
class UserRepository extends BaseRepository
{
    /**
     * getModel
     *
     * @return void
     */
    public function getModel()
    {
        return User::class;
    }

    /**
     * findByEmail
     *
     * @param string $email
     *
     * @return User|null
     *
     */
    public function findByEmail(string $email)
    {
        return $this->model->where([
            'email' => $email
        ])->first();
    }

    /**
     * update password
     *
     * @param string $email
     * @param string $randomPass
     *
     * @return User|null
     *
     */
    public function updatePassword(string $email, string $randomPass)
    {
        try {
            // update user
            $this->model->where([
                'email'     => $email
            ])->update([
                'password'  => Hash::make($randomPass)
            ]);
        } catch (\Throwable $th) {
            throw $th;
        }

        return true;
    }
}
