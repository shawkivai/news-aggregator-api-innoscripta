<?php

namespace App\Domain\V1\Article\Services;

use App\Domain\V1\Article\Transformer\TheGuardianTransformer;
use App\Jobs\TheGuardianJob;
use Illuminate\Support\Facades\Http;

class TheGuardianApiService
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

    public function setQueryParams(string $keyword): self
    {
        $queryParameters = 'q='.$keyword.'&from-date='.now()->subDays(1)->format('Y-m-d').'&api-key='.$this->apiKey;

        $this->queryParams = $queryParameters;

        return $this;
    }

    public function getArticles(int $newsSourceId, int $categoryId): void
    {
        $url = $this->baseUrl.'?'.$this->queryParams;

        TheGuardianJob::dispatch($url, $newsSourceId, $categoryId);
    }
}
