<?php

namespace App\Domain\V1\User\Services;

use Illuminate\Support\Facades\Auth;

class UserTokenService
{
    public static function accessToken(): string
    {
        return 'Bearer '.Auth::user()->createToken('access_token')->plainTextToken;
    }

    public static function refreshToken(): string
    {
        return 'Bearer '.Auth::user()->createToken('refresh_token')->plainTextToken;
    }
}
