<?php

namespace App\Domain\V1\Article\Services;

use App\DataTransferObjects\ServiceResponseDTO;
use App\Domain\V1\Article\Repositories\ArticleRepository;
use App\Domain\V1\Article\Transformer\ArticleTransformer;
use App\Enums\V1\HttpStatus;
use App\Traits\ServiceResponseTrait;
use Illuminate\Support\Facades\Config;

class ArticleService
{
    use ServiceResponseTrait;

    public function __construct(
        protected ArticleRepository $articleRepository
    ) {}

    public function getArticles($categoryId = null, $sourceId = null): ServiceResponseDTO
    {
        try {
            return $this->respondSuccess(
                ArticleTransformer::transform($this->articleRepository
                    ->setCategoryId($categoryId)
                    ->setSourceId($sourceId)
                    ->setLimit(Config::get('constants.pagination_limit.articles'))
                    ->setOffset(request()->get('offset', 0))
                    ->getArticles()
                ),
                HttpStatus::SUCCESS,
                'Articles fetched successfully',
            );
        } catch (\Throwable $th) {
            return $this->respondFailed($th->getMessage(), HttpStatus::INTERNAL_ERROR);
        }
    }

    public function getArticleDetails(int $articleId): ServiceResponseDTO
    {
        return $this->respondSuccess(
            ArticleTransformer::transform($this->articleRepository->getArticleById($articleId)),
            HttpStatus::SUCCESS,
            'Article details fetched successfully',
        );
    }

    public function searchArticles($query): ServiceResponseDTO
    {
        try {
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

            return $this->respondSuccess(
                ArticleTransformer::transform($this->articleRepository->searchArticles()),
                HttpStatus::SUCCESS,
                'Articles fetched successfully',
            );
        } catch (\Throwable $th) {
            return $this->respondFailed($th->getMessage(), HttpStatus::INTERNAL_ERROR);
        }
    }
}
