<?php

namespace App\Http\Controllers\V1;

use App\Domain\V1\NewsSource\Services\NewsAggregatorService;
use App\Http\Controllers\Controller;

class ArticleController extends Controller
{
    public function __construct(
        protected NewsAggregatorService $newsAggregatorService
    ) {}

    public function downloadNews()
    {
        return $this->newsAggregatorService->findAndStoreArticles();
    }
}
