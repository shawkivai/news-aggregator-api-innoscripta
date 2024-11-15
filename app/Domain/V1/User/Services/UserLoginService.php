<?php

namespace App\Domain\V1\User\Services;

use App\Domain\V1\User\Transformer\AuthUserTransformer;
use App\Enums\V1\HttpStatus;
use App\Traits\ServiceResponseTrait;
use Illuminate\Support\Facades\Auth;

class UserLoginService
{
    use ServiceResponseTrait;

    public function authenticate(array $data)
    {
        $userInfo = Auth::attempt($data);

        if (! $userInfo) {
            return $this->respondFailed('Invalid credentials', HttpStatus::UNAUTHORIZED, HttpStatus::FAILED_REQUEST);
        }

        return $this->respondSuccess(
            AuthUserTransformer::transform(Auth::user()),
            HttpStatus::SUCCESS,
            HttpStatus::SUCCESS,
            'User authenticated successfully'
        );
    }

    // private function authenticateUser(array $data): Authenticatable|null
    // {
    //     return Auth::getProvider()->retrieveByCredentials([
    //         'email' => $data['email'],
    //         'password' => $data['password'],
    //     ]);
    // }
}
