<?php

namespace App\Domain\V1\Article\Services;

use App\DataTransferObjects\ServiceResponseDTO;
use App\Domain\V1\Article\Repositories\ArticleRepository;
use App\Domain\V1\Article\Transformer\ArticleTransformer;
use App\Enums\V1\HttpStatus;
use App\Traits\ServiceResponseTrait;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;

class ArticleService
{
    use ServiceResponseTrait;

    public function __construct(
        protected ArticleRepository $articleRepository
    ) {}

    /**
     * Get articles by category and source
     *
     * @param  int|null  $categoryId
     * @param  int|null  $sourceId
     */
    public function getArticles($categoryId = null, $sourceId = null): ServiceResponseDTO
    {
        try {
            $articles = $this->articleRepository
                ->setCategoryId($categoryId)
                ->setSourceId($sourceId)
                ->setLimit(Config::get('constants.pagination_limit.articles'))
                ->setOffset(request()->get('offset', 0))
                ->getArticles();

            $articles = $articles->map(function ($article) {
                return ArticleTransformer::transform($article);
            });

            return $this->respondSuccess(
                $articles,
                HttpStatus::SUCCESS,
                'Articles fetched successfully',
            );
        } catch (\Throwable $th) {
            return $this->respondFailed($th->getMessage(), HttpStatus::INTERNAL_ERROR);
        }
    }

    /**
     * Get article details
     */
    public function getArticleDetails(int $articleId): ServiceResponseDTO
    {
        $article = Cache::remember('article_'.$articleId, 1440, function () use ($articleId) {
            return $this->articleRepository->getArticleById($articleId);
        });

        return $this->respondSuccess(
            ArticleTransformer::transform($article),
            HttpStatus::SUCCESS,
            'Article details fetched successfully',
        );
    }

    /**
     * Search articles
     *
     * @param  array  $query
     */
    public function searchArticles($query): ServiceResponseDTO
    {
        try {
            // Cache key for search articles
            $cacheKey = 'search_articles_'.json_encode([
                'keyword' => isset($query['keyword']) ? $query['keyword'] : null,
                'date' => isset($query['date']) ? $query['date'] : null,
                'category_id' => isset($query['category_id']) ? $query['category_id'] : null,
                'source_id' => isset($query['source_id']) ? $query['source_id'] : null,
                'offset' => request()->get('offset', 0),
            ]);

            $articles = Cache::remember($cacheKey, 1440, function () use ($query) {
                $keyword = isset($query['keyword']) ? $query['keyword'] : null;
                $date = isset($query['date']) ? $query['date'] : null;
                $categoryId = isset($query['category_id']) ? $query['category_id'] : null;
                $sourceId = isset($query['source_id']) ? $query['source_id'] : null;

                $this->articleRepository->setSearchKeyword($keyword)
                    ->setPublishedAt($date)
                    ->setCategoryId($categoryId)
                    ->setSourceId($sourceId)
                    ->setLimit(Config::get('constants.pagination_limit.articles'))
                    ->setOffset(request()->get('offset', 0));

                return $this->articleRepository->searchArticles();
            });

            $articles = $articles->map(function ($article) {
                return ArticleTransformer::transform($article);
            });

            return $this->respondSuccess(
                $articles,
                HttpStatus::SUCCESS,
                'Articles fetched successfully',
            );
        } catch (\Throwable $th) {
            return $this->respondFailed($th->getMessage(), HttpStatus::INTERNAL_ERROR);
        }
    }
}
