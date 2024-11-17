<?php

namespace App\Domain\V1\Article\Services;

use App\Domain\V1\Article\Repositories\ArticleRepository;
use App\Domain\V1\Article\Repositories\NewsSourceRepository;

class NewsAggregatorService
{
    public function __construct(
        protected NewsSourceRepository $newsSourceRepository,
        protected ArticleRepository $articleRepository
    ) {}

    public function findAndStoreArticles(): bool
    {
        $articles = [];

        $newsSources = $this->newsSourceRepository->all();

        $categories = $this->newsSourceRepository->newsCategories();

        foreach ($newsSources as $newsSource) {

            switch (strtolower($newsSource->name)) {
                case 'newsapi':
                    $newsApiService = new NewsApiService;
                    $newsApiArticles = $newsApiService->setApiKey($newsSource->api_key)
                        ->setBaseUrl($newsSource->base_url)
                        ->setQueryParams($categories)
                        ->getArticles($newsSource->id);

                    break;
                case 'the guardian':
                    $theGuardianApiService = new TheGuardianApiService;
                    $theGuardianArticles = $theGuardianApiService->setApiKey($newsSource->api_key)
                        ->setBaseUrl($newsSource->base_url)
                        ->setQueryParams($categories)
                        ->getArticles($newsSource->id);
                    break;
                case 'new york times':
                    $newYorkTimesApiService = new NewYorkTimesApiService;
                    $newYorkTimesArticles = [];
                    foreach ($categories as $category) {
                        $newYorkTimesArticles = array_merge($newYorkTimesArticles, $newYorkTimesApiService->setApiKey($newsSource->api_key)
                            ->setBaseUrl($newsSource->base_url)
                            ->setQueryParams($category)
                            ->getArticles($newsSource->id));
                    }
                    break;
            }
        }

        $articles = array_merge($newsApiArticles, $theGuardianArticles, $newYorkTimesArticles);

        return $this->articleRepository->bulkInsert($articles);
    }
}
