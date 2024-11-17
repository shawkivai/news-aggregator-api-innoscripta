<?php

namespace App\Domain\V1\User\Services;

use App\Domain\V1\User\Repositories\UserRepository;
use App\Domain\V1\User\Transformer\AuthUserTransformer;
use App\Enums\V1\HttpStatus;
use App\Traits\ServiceResponseTrait;
use Illuminate\Support\Facades\Auth;

class UserAuthenticationService
{
    use ServiceResponseTrait;

    public function __construct(
        private readonly UserRepository $userRepository
    ) {}

    public function authenticate(array $data)
    {
        $userInfo = Auth::attempt($data);

        if (! $userInfo) {
            return $this->respondFailed('Invalid credentials', HttpStatus::UNAUTHORIZED);
        }

        return $this->respondSuccess(
            AuthUserTransformer::transform(Auth::user()),
            HttpStatus::SUCCESS,
            'User authenticated successfully'
        );
    }

    public function logout()
    {
        $this->userRepository->logout();

        return $this->respondSuccess(
            null,
            HttpStatus::SUCCESS,
            'User logged out successfully'
        );
    }

    public function resetPassword(array $data)
    {
        try {
            if (! $this->userRepository->checkOldPassword($data['old_password'])) {
                return $this->respondFailed('Invalid old password', HttpStatus::BAD_REQUEST);
            }

            $this->userRepository->resetPassword($data['new_password']);

            return $this->respondSuccess(
                null,
                HttpStatus::SUCCESS,
                'Password reset successfully'
            );
        } catch (\Throwable $th) {
            return $this->respondFailed($th->getMessage(), HttpStatus::BAD_REQUEST);
        }
    }
}
