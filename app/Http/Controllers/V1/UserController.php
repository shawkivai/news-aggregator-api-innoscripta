<?php

namespace App\Http\Controllers\V1;

use App\Domain\V1\User\Services\UserAuthenticationService;
use App\Domain\V1\User\Services\UserRegistrationService;
use App\Enums\V1\HttpStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\PasswordResetRequest;
use App\Http\Requests\UserLoginRequest;
use App\Http\Requests\UserRegistrationRequest;
use App\Traits\ResponseTrait;
use Illuminate\Http\JsonResponse;

class UserController extends Controller
{
    /**
     * @OA\Info(
     *     title="Users API",
     *     version="1.0.0",
     *     description="API for user registration and login"
     * )
     *
     * @OA\SecurityScheme(
     *     securityScheme="BearerAuth",
     *     type="http",
     *     scheme="bearer",
     *     bearerFormat="JWT"
     * )
     */
    use ResponseTrait;

    public function __construct(
        protected UserRegistrationService $userRegistrationService,
        protected UserAuthenticationService $userAuthenticationService
    ) {}

    /**
     * Registers a new user.
     *
     * @OA\Post(
     *     path="/api/v1/register",
     *     summary="Registers a new user",
     *     description="Registers a new user with the provided details",
     *     tags={"User"},
     *
     *     @OA\RequestBody(
     *         description="User registration details",
     *         required=true,
     *
     *         @OA\JsonContent(
     *             type="object",
     *             required={"name", "email", "password"},
     *
     *             @OA\Property(property="name", type="string", example="John Doe"),
     *             @OA\Property(property="email", type="string", format="email", example="john.doe@example.com"),
     *             @OA\Property(property="password", type="string", format="password", example="password123"),
     *         ),
     *     ),
     *
     *     @OA\Response(
     *         response=201,
     *         description="User registered successfully",
     *
     *         @OA\JsonContent(
     *             type="object",
     *
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="User registered successfully"),
     *             @OA\Property(property="data", type="object"),
     *         ),
     *     ),
     *
     *     @OA\Response(
     *         response=400,
     *         description="Bad request",
     *
     *         @OA\JsonContent(
     *             type="object",
     *
     *             @OA\Property(property="status", type="string", example="failed"),
     *             @OA\Property(property="error", type="object", @OA\Property(property="code", type="integer", example=400), @OA\Property(property="description", type="string", example="Invalid request")),
     *         ),
     *     ),
     * )
     */
    public function register(UserRegistrationRequest $request): JsonResponse
    {
        try {
            return $this->handleResponse($this->userRegistrationService->execute($request->validated()));
        } catch (\Throwable $th) {
            return $this->apiFailedResponse(HttpStatus::INTERNAL_ERROR, $th->getMessage());
        }
    }

    /**
     * Logs in a user.
     *
     * @OA\Post(
     *     path="/api/v1/login",
     *     summary="Logs in a user",
     *     description="Logs in a user with the provided credentials",
     *     tags={"User"},
     *
     *     @OA\RequestBody(
     *         description="User login details",
     *         required=true,
     *
     *         @OA\JsonContent(
     *             type="object",
     *             required={"email", "password"},
     *
     *             @OA\Property(property="email", type="string", format="email", example="john.doe@example.com"),
     *             @OA\Property(property="password", type="string", format="password", example="password123"),
     *         ),
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="User logged in successfully",
     *
     *         @OA\JsonContent(
     *             type="object",
     *
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="User logged in successfully"),
     *             @OA\Property(property="data", type="object"),
     *         ),
     *     ),
     *
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *
     *         @OA\JsonContent(
     *             type="object",
     *
     *             @OA\Property(property="status", type="string", example="failed"),
     *             @OA\Property(property="error", type="object", @OA\Property(property="code", type="integer", example=401), @OA\Property(property="description", type="string", example="Unauthorized")),
     *         ),
     *     ),
     * )
     */
    public function login(UserLoginRequest $request): JsonResponse
    {
        try {
            return $this->handleResponse($this->userAuthenticationService->authenticate($request->validated()));
        } catch (\Throwable $th) {
            return $this->apiFailedResponse(HttpStatus::INTERNAL_ERROR, $th->getMessage());
        }
    }

    /**
     * Logs out a user.
     *
     * @OA\Post(
     *     path="/api/v1/logout",
     *     summary="Logs out a user",
     *     description="Logs out a user",
     *     tags={"User"},
     *     security={{"BearerAuth": {}}},
     *
     *     @OA\Response(
     *         response=200,
     *         description="User logged out successfully",
     *     ),
     * )
     */
    public function logout(): JsonResponse
    {
        try {
            return $this->handleResponse($this->userAuthenticationService->logout());
        } catch (\Throwable $th) {
            return $this->apiFailedResponse(HttpStatus::INTERNAL_ERROR, $th->getMessage());
        }
    }

    /**
     * Resets a user's password.
     *
     * @OA\Patch(
     *     path="/api/v1/reset-password",
     *     summary="Resets a user's password",
     *     description="Resets a user's password",
     *     tags={"User"},
     *     security={{"BearerAuth": {}}},
     *
     *     @OA\RequestBody(
     *         description="User reset password details",
     *         required=true,
     *
     *         @OA\JsonContent(
     *             type="object",
     *             required={"old_password", "new_password"},
     *
     *             @OA\Property(property="old_password", type="string", example="oldPassword123"),
     *             @OA\Property(property="new_password", type="string", example="newPassword123"),
     *         ),
     *     ),
     *
     *  @OA\Response(
     *         response=200,
     *         description="Password reset successfully",
     *     ),
     *  @OA\Response(
     *         response=422,
     *         description="Validation error",
     *
     *         @OA\JsonContent(
     *             type="object",
     *
     *             @OA\Property(property="message", type="string", example="The given data was invalid."),
     *             @OA\Property(
     *                 property="errors",
     *                 type="object",
     *                 @OA\Property(property="old_password", type="array", @OA\Items(type="string", example="The old password field is required.")),
     *                     @OA\Property(property="new_password", type="array", @OA\Items(type="string", example="The new password field is required."))
     *                 )
     *             ),
     *         ),
     * )
     */
    public function resetPassword(PasswordResetRequest $request): JsonResponse
    {
        try {
            return $this->handleResponse($this->userAuthenticationService->resetPassword($request->validated()));
        } catch (\Throwable $th) {
            return $this->apiFailedResponse(HttpStatus::INTERNAL_ERROR, $th->getMessage());
        }
    }
}
