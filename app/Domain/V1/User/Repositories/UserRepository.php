<?php

namespace App\Domain\V1\User\Repositories;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserRepository
{
    public function create(array $data): User
    {
        return User::create($data);
    }

    public function checkOldPassword(string $oldPassword): bool
    {
        return Hash::check($oldPassword, Auth::user()->password);
    }

    public function resetPassword(string $password): bool
    {
        return Auth::user()->update([
            'password' => Hash::make($password),
        ]);
    }

    public function logout(): mixed
    {
        return Auth::user()->tokens()->delete();
    }
}
