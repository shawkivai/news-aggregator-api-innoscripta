<?php

namespace App\Domain\V1\Article\Repositories;

use App\Models\Article;

class ArticleRepository
{
    public function bulkInsert(array $articles): bool
    {
        return Article::insert($articles);
    }
}
