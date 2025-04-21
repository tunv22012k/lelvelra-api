<?php

namespace App\Models;

/**
 * RefreshToken
 */
class RefreshToken extends BaseModel
{
    protected $table = 'refresh_tokens';

    /**
     * get user
     *
     * @return void
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
