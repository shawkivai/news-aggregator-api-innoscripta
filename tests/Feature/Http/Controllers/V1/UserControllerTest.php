<?php

namespace Tests\Feature\Http\Controllers\V1;

use App\Enums\V1\HttpStatus;
use App\Models\User;
use Illuminate\Support\Str;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    public function test_user_registration_success(): void
    {
        $response = $this->postJson('/api/v1/register', [
            'name' => Str::random(10),
            'email' => Str::random(6).'@example.com',
            'password' => 'Password@123',
        ]);
        $response->assertStatus(HttpStatus::CREATED);
    }

    public function test_user_registration_failure(): void
    {
        $response = $this->postJson('/api/v1/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'ASDFGHJKL',
        ]);
        $response->assertStatus(HttpStatus::VALIDATION_ERROR);
    }

    public function test_user_login_success(): void
    {
        $user = User::factory()->create([
            'name' => 'Test User',
            'email' => Str::random(6).'@example.com',
            'password' => 'Shawki@1995',
        ]);
        $response = $this->postJson('/api/v1/login', [
            'email' => $user->email,
            'password' => 'Shawki@1995',
        ]);

        $response->assertStatus(HttpStatus::SUCCESS);
    }

    public function test_user_unauthenticated_if_invalid_credentials(): void
    {
        $response = $this->postJson('/api/v1/login', [
            'email' => 'test@shawki.com',
            'password' => 'Password@1234',
        ]);

        $response->assertStatus(HttpStatus::UNAUTHORIZED);
    }

    public function test_user_logout_successfully(): void
    {
        $user = User::factory()->create([
            'name' => 'Test User',
            'email' => Str::random(6).'@example.com',
            'password' => 'Shawki@1995',
        ]);
        $token = $user->createToken('test-token')->plainTextToken;

        $response = $this->postJson('/api/v1/logout', [
            'email' => $user->email,
            'password' => 'Shawki@1995',
        ], [
            'Authorization' => 'Bearer '.$token,
        ]);
        $response->assertStatus(HttpStatus::SUCCESS);
    }

    public function test_user_can_reset_password_successfully(): void
    {
        $user = User::factory()->create([
            'name' => 'Test User',
            'email' => Str::random(6).'@example.com',
            'password' => 'Shawki@1995',
        ]);

        $token = $user->createToken('test-token')->plainTextToken;

        $response = $this->patchJson('/api/v1/reset-password', [
            'old_password' => 'Shawki@1995',
            'new_password' => 'Password@123',
        ], [
            'Authorization' => 'Bearer '.$token,
        ]);
        $response->assertStatus(HttpStatus::SUCCESS);
    }
}
