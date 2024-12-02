<?php

namespace App\Http\Controllers\V1;

use App\Domain\V1\Article\Services\ArticleService;
use App\Enums\V1\HttpStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\ArticleSearchRequest;
use App\Traits\ResponseTrait;
use Illuminate\Http\JsonResponse;

class ArticleController extends Controller
{
    use ResponseTrait;

    public function __construct(
        protected ArticleService $articleService
    ) {}

    /**
     * @OA\Get(
     *     path="/api/v1/articles/details/{articleId}",
     *     tags={"Articles"},
     *     summary="Get article details",
     *     description="Fetches article details by ID",
     *     security={{"BearerAuth": {}}},
     *
     *     @OA\Parameter(
     *         name="articleId",
     *         in="path",
     *         description="Article ID to fetch details",
     *         required=true,
     *
     *         @OA\Schema(
     *             type="integer",
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Article details fetched successfully",
     *
     *         @OA\JsonContent(
     *             type="object",
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=500,
     *         description="Internal Server Error",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="message", type="string", example="Something went wrong")
     *         )
     *     )
     * )
     */
    public function view(int $articleId)
    {
        try {
            return $this->handleResponse($this->articleService->getArticleDetails($articleId));
        } catch (\Throwable $th) {
            return $this->apiFailedResponse(HttpStatus::INTERNAL_ERROR, $th->getMessage());
        }
    }

    /**
     * @OA\Get(
     *     path="/api/v1/articles/{categoryId}/{sourceId}",
     *     tags={"Articles"},
     *     summary="Get articles",
     *     description="Fetches articles based on category ID, source ID, and offset",
     *     security={{"BearerAuth": {}}},
     *
     *     @OA\Parameter(
     *         name="categoryId",
     *         in="path",
     *         description="Category ID to filter articles",
     *         required=false,
     *
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *
     *     @OA\Parameter(
     *         name="sourceId",
     *         in="path",
     *         description="Source ID to filter articles",
     *         required=false,
     *
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *
     *     @OA\Parameter(
     *         name="offset",
     *         in="query",
     *         description="Offset for pagination",
     *         required=false,
     *
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Articles fetched successfully",
     *
     *         @OA\JsonContent(
     *             type="collection",
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=500,
     *         description="Internal Server Error",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="Something went wrong"
     *             )
     *         )
     *     )
     * )
     */
    public function index($categoryId = null, $sourceId = null): JsonResponse
    {
        try {
            return $this->handleResponse($this->articleService->getArticles($categoryId, $sourceId));
        } catch (\Throwable $th) {
            return $this->apiFailedResponse(HttpStatus::INTERNAL_ERROR, $th->getMessage());
        }
    }

    /**
     * @OA\Get(
     *     path="/api/v1/search-articles",
     *     tags={"Articles"},
     *     summary="Search articles",
     *     description="Searches articles based on the provided query",
     *     security={{"BearerAuth": {}}},
     *
     *     @OA\Parameter(
     *         name="keyword",
     *         in="query",
     *         description="Keyword to search articles",
     *         required=false,
     *
     *         @OA\Schema(
     *             type="string",
     *         )
     *     ),
     *
     *     @OA\Parameter(
     *         name="date",
     *         in="query",
     *         description="Published date to search articles",
     *         required=false,
     *
     *         @OA\Schema(
     *             type="date",
     *         )
     *     ),
     *
     *     @OA\Parameter(
     *         name="category_id",
     *         in="query",
     *         description="Category ID to search articles",
     *         required=false,
     *
     *         @OA\Schema(
     *             type="integer",
     *         )
     *     ),
     *
     *     @OA\Parameter(
     *         name="source_id",
     *         in="query",
     *         description="Source ID to search articles",
     *         required=false,
     *
     *         @OA\Schema(
     *             type="integer",
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Articles fetched successfully",
     *
     *         @OA\JsonContent(
     *             type="collection",
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=500,
     *         description="Internal Server Error",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="message", type="string", example="Something went wrong")
     *         )
     *     )
     * )
     */
    public function search(ArticleSearchRequest $request): JsonResponse
    {
        try {
            return $this->handleResponse($this->articleService->searchArticles($request->query()));
        } catch (\Throwable $th) {
            return $this->apiFailedResponse(HttpStatus::INTERNAL_ERROR, $th->getMessage());
        }
    }
}
