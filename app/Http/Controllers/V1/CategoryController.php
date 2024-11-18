<?php

namespace App\Http\Controllers\V1;

use App\Domain\V1\Category\Services\CategoryService;
use App\Enums\V1\HttpStatus;
use App\Http\Controllers\Controller;
use App\Traits\ResponseTrait;
use Illuminate\Http\JsonResponse;

class CategoryController extends Controller
{
    use ResponseTrait;

    public function __construct(
        protected CategoryService $categoryService
    ) {}

    /**
     * @OA\Get(
     *     path="/api/v1/categories",
     *     tags={"Categories"},
     *
     *     @OA\Response(response=200, description="Get all categories")
     * )
     */
    public function index(): JsonResponse
    {
        try {
            return $this->handleResponse($this->categoryService->all());
        } catch (\Throwable $th) {
            return $this->apiFailedResponse(HttpStatus::INTERNAL_ERROR, $th->getMessage());
        }
    }
}
