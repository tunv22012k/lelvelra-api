<?php

namespace App\Repositories;

use App\Models\RefreshToken;
use App\Repositories\BaseRepository;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;

/**
 * RefreshTokenRepository
 */
class RefreshTokenRepository extends BaseRepository
{
    /**
     * getModel
     *
     * @return void
     */
    public function getModel()
    {
        return RefreshToken::class;
    }

    /**
     * findByToken
     *
     * @param string $token
     *
     * @return RefreshToken|null
     *
     */
    public function findByToken(string $token)
    {
        return $this->model->where([
            'token' => $token,
            'revoked' => false
        ])
        ->where('expires_at', '>', Carbon::now())
        ->first();
    }
}
