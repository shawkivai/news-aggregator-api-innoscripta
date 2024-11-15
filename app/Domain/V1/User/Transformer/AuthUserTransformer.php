<?php

namespace App\Domain\V1\User\Transformer;

use App\Domain\V1\User\DTO\AuthUserDTO;
use App\Domain\V1\User\Services\UserTokenService;

class AuthUserTransformer
{
    public static function transform($data): array
    {
        return (new AuthUserDTO([
            'id' => $data->id,
            'name' => $data->name,
            'email' => $data->email,
            'access_token' => UserTokenService::accessToken(),
            'refresh_token' => UserTokenService::refreshToken(),
        ]))->process();
    }
}
