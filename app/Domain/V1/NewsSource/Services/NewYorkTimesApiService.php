<?php

namespace App\Domain\V1\NewsSource\Services;

use App\Contacts\V1\NewsSourceInterface;
use App\Domain\V1\NewsSource\Transformer\NewYorkTimesTransformer;
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

    public function getArticles(int $newsSourceId): array
    {
        $url = $this->baseUrl.'?'.$this->queryParams;

        $response = Http::get($url);

        if ($response->successful()) {
            $articles = $response->json();
            if (! empty($articles['response']['docs'])) {
                $randomArticles = array_rand($articles['response']['docs'], min(count($articles['response']['docs']), 100));
                $articles = array_intersect_key($articles['response']['docs'], array_flip($randomArticles));

                return $this->processArticles($articles, $newsSourceId);
            }
        }

        return [];
    }

    private function processArticles(array $articles, int $newsSourceId): array
    {
        return array_map(function ($article) use ($newsSourceId) {
            return NewYorkTimesTransformer::transform($article, $newsSourceId);
        }, $articles);
    }
}
