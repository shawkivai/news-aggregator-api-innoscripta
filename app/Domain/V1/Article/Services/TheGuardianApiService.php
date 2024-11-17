<?php

namespace App\Domain\V1\Article\Services;

use App\Domain\V1\Article\Transformer\TheGuardianTransformer;
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

    public function setQueryParams(array $categories): self
    {
        // Separate keywords with comma and add to query parameters
        $searchTerms = implode('%20OR%20', $categories);

        $queryParameters = 'q='.$searchTerms.'&from-date='.now()->subDays(1)->format('Y-m-d').'&api-key='.$this->apiKey;

        $this->queryParams = $queryParameters;

        return $this;
    }

    public function getArticles(int $newsSourceId)
    {
        $url = $this->baseUrl.'?'.$this->queryParams;

        $response = Http::get($url);

        if ($response->successful() && ! empty($response->json())) {
            $articles = $response->json();
            if (isset($articles['response']['results'])) {
                $randomArticles = array_rand($articles['response']['results'], min(count($articles['response']['results']), 100));
                $articles = array_intersect_key($articles['response']['results'], array_flip($randomArticles));

                return $this->processArticles($articles, $newsSourceId);
            }
        }

        return [];
    }

    private function processArticles(array $articles, int $newsSourceId): array
    {
        return array_map(function ($article) use ($newsSourceId) {
            return TheGuardianTransformer::transform($article, $newsSourceId);
        }, $articles);
    }
}
