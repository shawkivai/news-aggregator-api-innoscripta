<?php

namespace App\Domain\V1\User\Services;

use App\DataTransferObjects\ServiceResponseDTO;
use App\Domain\V1\Article\Repositories\ArticleRepository;
use App\Domain\V1\Article\Transformer\ArticleTransformer;
use App\Domain\V1\User\Repositories\UserPreferenceRepository;
use App\Domain\V1\User\Transformer\UserPreferenceTransformer;
use App\Enums\V1\HttpStatus;
use App\Models\Category;
use App\Models\NewsSource;
use App\Traits\ServiceResponseTrait;
use Illuminate\Support\Facades\Config;

class UserPreferenceService
{
    use ServiceResponseTrait;

    public function __construct(
        protected UserPreferenceRepository $userPreferenceRepository,
        protected ArticleRepository $articleRepository
    ) {}

    public function update(array $data): ServiceResponseDTO
    {
        try {
            if (isset($data['sources'])) {
                foreach ($data['sources'] as $source) {
                    $sourceModel = NewsSource::find($source);
                    $sourceModel->userPreferences()->updateOrCreate(
                        ['user_id' => auth()->id(), 'preference_id' => $sourceModel->id],
                        [
                            'preference_type' => $sourceModel->getMorphClass(),
                        ]
                    );
                }
            }

            if (isset($data['categories'])) {
                foreach ($data['categories'] as $category) {
                    $categoryModel = Category::find($category);
                    $categoryModel->userPreferences()->updateOrCreate(
                        ['user_id' => auth()->id(), 'preference_id' => $categoryModel->id],
                        [
                            'preference_type' => $categoryModel->getMorphClass(),
                        ]
                    );
                }
            }

            return $this->respondSuccess(
                null,
                HttpStatus::SUCCESS,
                'User preferences updated successfully',
            );
        } catch (\Throwable $th) {
            return $this->respondFailed(
                $th->getMessage(),
                HttpStatus::INTERNAL_ERROR
            );
        }
    }

    public function getUserPreferences(): ServiceResponseDTO
    {
        try {
            return $this->respondSuccess(
                UserPreferenceTransformer::transform($this->userPreferenceRepository->setUserId(auth()->id())->getUserPreferences()),
                HttpStatus::SUCCESS,
                'User preferences fetched successfully',
            );
        } catch (\Throwable $th) {
            return $this->respondFailed(
                $th->getMessage(),
                HttpStatus::INTERNAL_ERROR
            );
        }
    }

    public function getNewsfeed(): ServiceResponseDTO
    {
        try {
            return $this->respondSuccess(
                ArticleTransformer::transform($this->articleRepository
                    ->setLimit(Config::get('constants.pagination_limit.articles'))
                    ->setOffset(request()->get('offset', 0))
                    ->getArticlesByUserPreferences()
                ),
                HttpStatus::SUCCESS,
                'Newsfeed fetched successfully',
            );
        } catch (\Throwable $th) {
            return $this->respondFailed(
                $th->getMessage(),
                HttpStatus::INTERNAL_ERROR
            );
        }
    }
}
