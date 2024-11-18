<?php

namespace App\Domain\V1\User\Services;

use App\DataTransferObjects\ServiceResponseDTO;
use App\Domain\V1\User\Repositories\UserPreferenceRepository;
use App\Enums\V1\HttpStatus;
use App\Models\Category;
use App\Models\NewsSource;
use App\Traits\ServiceResponseTrait;

class UserPreferenceService
{
    use ServiceResponseTrait;

    public function __construct(
        protected UserPreferenceRepository $userPreferenceRepository
    ) {}

    public function update(array $data): ServiceResponseDTO
    {
        try {
            if(isset($data['sources'])) {
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

            if(isset($data['categories'])) {
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
                $this->userPreferenceRepository->setUserId(auth()->id())->getUserPreferences(),
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
}
