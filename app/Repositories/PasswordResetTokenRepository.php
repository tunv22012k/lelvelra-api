<?php

namespace App\Repositories;

use App\Models\PasswordResetToken;
use App\Repositories\BaseRepository;

/**
 * PasswordResetTokenRepository
 */
class PasswordResetTokenRepository extends BaseRepository
{
    /**
     * getModel
     *
     * @return void
     */
    public function getModel()
    {
        return PasswordResetToken::class;
    }

    /**
     * findByToken
     *
     * @param string $token
     *
     * @return PasswordResetToken|null
     *
     */
    public function findByToken(string $token)
    {
        return $this->model->where([
            'token' => $token
        ])->first();
    }
}
