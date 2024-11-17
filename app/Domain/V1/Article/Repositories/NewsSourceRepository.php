<?php

namespace App\Domain\V1\Article\Repositories;

use App\Models\Category;
use App\Models\NewsSource;
use Illuminate\Support\Collection;

class NewsSourceRepository
{
    public function all(): Collection
    {
        return NewsSource::where('status', 1)->take(3)->get();
    }

    public function newsCategories(): Collection
    {
        return Category::where('status', 1)->select('id', 'name')->get();
    }
}
