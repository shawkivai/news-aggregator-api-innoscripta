<?php

namespace App\Domain\V1\Category\Services;

use App\Domain\V1\Category\Repositories\CategoryRepository;
use App\Enums\V1\HttpStatus;
use App\Traits\ServiceResponseTrait;
use Illuminate\Support\Facades\Cache;

class CategoryService
{
    use ServiceResponseTrait;

    public function __construct(
        protected CategoryRepository $categoryRepository
    ) {}

    public function all()
    {
        try {
            $categories = Cache::remember('categories', 1440, function () {
                return $this->categoryRepository->all();
            });

            return $this->respondSuccess(
                $categories,
                HttpStatus::SUCCESS,
                'Categories fetched successfully'
            );
        } catch (\Throwable $th) {
            return $this->respondFailed($th->getMessage(), HttpStatus::INTERNAL_ERROR);
        }
    }
}
