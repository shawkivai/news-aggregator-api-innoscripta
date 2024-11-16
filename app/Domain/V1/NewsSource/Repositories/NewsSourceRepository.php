<?php

namespace App\Domain\V1\NewsSource\Repositories;

use App\Models\Category;
use App\Models\NewsSource;
use Illuminate\Support\Collection;

class NewsSourceRepository
{
    public function all(): Collection
    {
        return NewsSource::where('status', 1)->inRandomOrder()->take(5)->get();
    }

    public function newsCategories(): array
    {
        return Category::where('status', 1)->get()->pluck('name')->toArray();
    }
}
