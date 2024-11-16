<?php

namespace App\Domain\V1\NewsSource\Repositories;

use App\Models\Article;

class ArticleRepository
{
    public function bulkInsert(array $articles): bool
    {
        return Article::insert($articles);
    }
}
