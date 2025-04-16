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

    /**
     * filter
     *
     * @param [type] $params
     */
    public function filter($params)
    {
        $query = $this->model->query();

        // filter first_name + last_name
        if (!empty($params['name'])) {
            $this->whereName($query, $params['name']);
        }

        // filter email
        if (!empty($params['email'])) {
            $this->whereEmail($query, $params['email']);
        }

        // filter address
        if (!empty($params['address'])) {
            $this->whereAddress($query, $params['address']);
        }

        return $query;
    }

    /**
     * where name
     *
     * @param $query
     * @param $name
     * @return void
     */
    public function whereName($query, $name)
    {
        $query->whereRaw("CONCAT(first_name, ' ', last_name) LIKE ?", ['%' . $name . '%']);
    }

    /**
     * where address
     *
     * @param $query
     * @param $address
     * @return void
     */
    public function whereAddress($query, $address)
    {
        $query->where('address', 'like', '%' . $address . '%');
    }

    /**
     * where email
     *
     * @param $query
     * @param $email
     * @return void
     */
    public function whereEmail($query, $email)
    {
        $query->where('email', 'like', '%' . $email . '%');
    }
}
