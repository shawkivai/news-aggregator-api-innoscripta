<?php

namespace App\Domain\V1\Article\Services;

use App\Jobs\TheGuardianJob;

class TheGuardianApiService
{
    protected string $apiKey;

    protected string $baseUrl;

    protected string $queryParams;

    /**
     * Set API key
     */
    public function setApiKey(string $apiKey): self
    {
        $this->apiKey = $apiKey;

        return $this;
    }

    /**
     * Set base URL
     */
    public function setBaseUrl(string $baseUrl): self
    {
        $this->baseUrl = $baseUrl;

        return $this;
    }

    /**
     * Set query parameters
     */
    public function setQueryParams(string $keyword): self
    {
        $queryParameters = 'q='.$keyword.'&from-date='.now()->subDays(1)->format('Y-m-d').'&api-key='.$this->apiKey;

        $this->queryParams = $queryParameters;

        return $this;
    }

    /**
     * Get articles
     */
    public function getArticles(int $newsSourceId, int $categoryId): void
    {
        $url = $this->baseUrl.'?'.$this->queryParams;

        TheGuardianJob::dispatch($url, $newsSourceId, $categoryId);
    }
}
