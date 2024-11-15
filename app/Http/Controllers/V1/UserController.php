<?php

namespace App\Http\Controllers\V1;

use App\Domain\V1\User\Services\UserLoginService;
use App\Domain\V1\User\Services\UserRegistrationService;
use App\Enums\V1\HttpStatus;
use App\Http\Controllers\Controller;
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
     */
    use ResponseTrait;

    public function __construct(
        private readonly UserRegistrationService $userRegistrationService,
        private readonly UserLoginService $userLoginService
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
            return $this->apiFailedResponse(HttpStatus::BAD_REQUEST, HttpStatus::FAILED_REQUEST, $th->getMessage());
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
            return $this->handleResponse($this->userLoginService->authenticate($request->validated()));
        } catch (\Throwable $th) {
            return $this->apiFailedResponse(HttpStatus::BAD_REQUEST, HttpStatus::FAILED_REQUEST, $th->getMessage());
        }
    }
}
