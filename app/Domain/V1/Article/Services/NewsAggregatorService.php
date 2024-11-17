<?php

namespace App\Domain\V1\Article\Services;

use App\Domain\V1\Article\Repositories\ArticleRepository;
use App\Domain\V1\Article\Repositories\NewsSourceRepository;
use App\Enums\V1\NewsSourceEnum;

class NewsAggregatorService
{
    public function __construct(
        protected NewsSourceRepository $newsSourceRepository,
        protected ArticleRepository $articleRepository
    ) {}

    public function findAndStoreArticles(): bool
    {
        try {
            $newsSources = $this->newsSourceRepository->all();

            $categories = $this->newsSourceRepository->newsCategories();

            foreach ($newsSources as $newsSource) {

                $serviceMap = [
                    NewsSourceEnum::NEWSAPI->value => NewsApiService::class,
                    NewsSourceEnum::THE_GUARDIAN->value => TheGuardianApiService::class,
                    NewsSourceEnum::NEW_YORK_TIMES->value => NewYorkTimesApiService::class,
                ];

                $serviceName = strtolower($newsSource->name);
                if (isset($serviceMap[$serviceName])) {
                    $service = new $serviceMap[$serviceName];
                    foreach ($categories as $category) {
                        $service->setApiKey($newsSource->api_key)
                            ->setBaseUrl($newsSource->base_url)
                            ->setQueryParams($category->name)
                            ->getArticles($newsSource->id, $category->id);
                    }
                }
            }

            return true;

        } catch (\Throwable $th) {
            return false;
        }
    }
}
