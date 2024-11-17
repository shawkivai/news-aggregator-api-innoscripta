<?php

namespace App\Domain\V1\Article\Services;

use App\Contacts\V1\NewsSourceInterface;
use App\Domain\V1\Article\Transformer\NewsApiTransformer;
use Illuminate\Support\Facades\Http;

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

    public function setQueryParams(array|string $keywords): self
    {
        // Separate keywords with comma and add to query parameters
        $searchTerms = implode(' OR ', $keywords);
        // dd($searchTerms);
        $queryParameters = 'q='.$searchTerms.'&from='.now()->subDays(1)->format('Y-m-d').'&sortBy=publishedAt'.'&apiKey='.$this->apiKey;

        $this->queryParams = $queryParameters;

        return $this;
    }

    public function getArticles(int $newsSourceId): array
    {
        $url = $this->baseUrl.'?'.$this->queryParams;

        $response = Http::get($url);

        $articles = $response->json();
        if ($response->successful() && ! empty($articles['articles'])) {
            $randomArticles = array_rand($articles['articles'], min(count($articles['articles']), 100));
            $articles = array_intersect_key($articles['articles'], array_flip($randomArticles));

            return $this->processArticles($articles, $newsSourceId);
        }

        return [];
    }

    private function processArticles(array $articles, int $newsSourceId): array
    {
        foreach ($articles as $article) {
            if (isset($article['source']['name']) && $article['source']['name'] != '[Removed]') {
                $articlesDTO[] = NewsApiTransformer::transform($article, $newsSourceId);
            }
        }

        return $articlesDTO;
    }
}
