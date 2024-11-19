<?php

namespace App\Domain\V1\Article\Services;

use App\Contacts\V1\NewsSourceInterface;
use App\Jobs\NewYorkTimesJob;

class NewYorkTimesApiService implements NewsSourceInterface
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
    public function setQueryParams(array|string $searchTerms): self
    {
        $this->queryParams = 'q='.$searchTerms.'&sort=newest'.'&page=1'.'&api-key='.$this->apiKey;

        return $this;
    }

    /**
     * Get articles
     */
    public function getArticles(int $newsSourceId, int $categoryId): void
    {
        $url = $this->baseUrl.'?'.$this->queryParams;

        NewYorkTimesJob::dispatch($url, $newsSourceId, $categoryId);
    }
}
