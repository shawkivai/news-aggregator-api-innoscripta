<?php

namespace App\Domain\V1\Article\Services;

use App\Contacts\V1\NewsSourceInterface;
use App\Domain\V1\Article\Transformer\NewYorkTimesTransformer;
use App\Jobs\NewYorkTimesJob;
use Illuminate\Support\Facades\Http;

class NewYorkTimesApiService implements NewsSourceInterface
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

    public function setQueryParams(array|string $searchTerms): self
    {
        $this->queryParams = 'q='.$searchTerms.'&sort=newest'.'&page=1'.'&api-key='.$this->apiKey;

        return $this;
    }

    public function getArticles(int $newsSourceId, int $categoryId): void
    {
        $url = $this->baseUrl.'?'.$this->queryParams;

        NewYorkTimesJob::dispatch($url, $newsSourceId, $categoryId);
    }
}
