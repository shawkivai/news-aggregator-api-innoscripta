<?php

namespace App\Domain\V1\Category\Services;

use App\Domain\V1\Category\Repositories\CategoryRepository;
use App\Enums\V1\HttpStatus;
use App\Traits\ServiceResponseTrait;

class CategoryService
{
    use ServiceResponseTrait;

    public function __construct(
        protected CategoryRepository $categoryRepository
    ) {}

    public function all()
    {
        try {
            return $this->respondSuccess(
                $this->categoryRepository->all(),
                HttpStatus::SUCCESS,
                'Categories fetched successfully'
            );
        } catch (\Throwable $th) {
            return $this->respondFailed($th->getMessage(), HttpStatus::INTERNAL_ERROR);
        }
    }
}
