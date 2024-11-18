<?php

namespace App\Http\Controllers\V1;

use App\Domain\V1\User\Services\UserPreferenceService;
use App\Enums\V1\HttpStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\UserPreferenceRequest;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;

class UserPreferenceController extends Controller
{
    use ResponseTrait;

    public function __construct(
        protected UserPreferenceService $userPreferenceService
    ) {}

    /**
     * @OA\Post(
     *     path="/api/v1/user/preferences",
     *     tags={"User Preferences"},
     *     summary="Update user preferences",
     *     description="This endpoint updates the user's preferences.",
     *     security={{"BearerAuth": {}}},
     *
     *     @OA\RequestBody(
     *         description="User preference data",
     *         required=true,
     *
     *         @OA\JsonContent(
     *             type="object",
     *
     *             @OA\Property(property="categories", type="array", @OA\Items(type="integer", example=1)),
     *             @OA\Property(property="sources", type="array", @OA\Items(type="integer", example=1))
     *         ),
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="User preferences updated successfully",
     *
     *         @OA\JsonContent(
     *             type="object",
     *
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="User preferences updated successfully"),
     *             @OA\Property(property="data", type="object")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=400,
     *         description="Bad request",
     *
     *         @OA\JsonContent(
     *             type="object",
     *
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="error", type="object",
     *                 @OA\Property(property="code", type="integer", example=400),
     *                 @OA\Property(property="description", type="string", example="Invalid request")
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=500,
     *         description="Internal server error",
     *
     *         @OA\JsonContent(
     *             type="object",
     *
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="error", type="object",
     *                 @OA\Property(property="code", type="integer", example=500),
     *                 @OA\Property(property="description", type="string", example="Something went wrong")
     *             )
     *         )
     *     )
     * )
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @throws \Throwable
     */
    public function __invoke(UserPreferenceRequest $request)
    {
        try {
            return $this->handleResponse($this->userPreferenceService->update($request->validated()));
        } catch (\Throwable $th) {
            return $this->apiFailedResponse(HttpStatus::INTERNAL_ERROR, $th->getMessage());
        }
    }

    /**
     * @OA\Get(
     *     path="/api/v1/user/preferences",
     *     tags={"User Preferences"},
     *     summary="Get user preferences",
     *     description="This endpoint fetches the user's preferences.",
     *
     *     @OA\Response(response=200, description="User preferences fetched successfully"),
     *     @OA\Response(response=500, description="Internal server error"),
     *     security={{"BearerAuth": {}}}
     * )
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        try {
            return $this->handleResponse($this->userPreferenceService->getUserPreferences());
        } catch (\Throwable $th) {
            return $this->apiFailedResponse(HttpStatus::INTERNAL_ERROR, $th->getMessage());
        }
    }
}
