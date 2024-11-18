<?php

namespace App\Domain\V1\Category\Repositories;

use App\Enums\V1\StatusEnum;
use App\Models\Category;

class CategoryRepository
{
    public function __construct(
        protected Category $category
    ) {}

    public function all()
    {
        return $this->category->where('status', StatusEnum::ACTIVE->value)->select('id', 'name')->get();
    }
}
