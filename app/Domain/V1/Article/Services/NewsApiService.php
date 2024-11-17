<?php

namespace App\Domain\V1\Article\Services;

use App\Contacts\V1\NewsSourceInterface;
use App\Domain\V1\Article\Transformer\NewsApiTransformer;
use App\Jobs\NewsApiJob;

class NewsApiService implements NewsSourceInterface
{
    protected string $apiKey;

    protected string $baseUrl;

    protected string $queryParams;

    public function setApiKey(string $apiKey): self
    {
        $this->apiKey = $apiKey;

        return $this;
    }

    public function setBaseUrl(string $baseUrl): self
    {
        $this->baseUrl = $baseUrl;

        return $this;
    }

    public function setQueryParams(array|string $category): self
    {
        $queryParameters = 'q='.$category.'&from='.now()->subDays(1)->format('Y-m-d').'&pageSize=100'.'&sortBy=publishedAt'.'&apiKey='.$this->apiKey;

        $this->queryParams = $queryParameters;

        return $this;
    }

    public function getArticles(int $newsSourceId, int $categoryId): void
    {
        $url = $this->baseUrl.'?'.$this->queryParams;

        NewsApiJob::dispatch($url, $newsSourceId, $categoryId);
    }
}
