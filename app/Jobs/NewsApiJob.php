<?php

namespace App\Jobs;

use App\Domain\V1\Article\Repositories\ArticleRepository;
use App\Domain\V1\Article\Transformer\NewsApiTransformer;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Http;

class NewsApiJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(private string $url, private int $newsSourceId, private int $categoryId) {}

    /**
     * Execute the job.
     */
    public function handle(): bool
    {
        $response = Http::get($this->url);

        $articles = $response->json();
        if ($response->successful() && ! empty($articles['articles'])) {
            //TODO:: Process articles chunk by chunk
            $processedArticles = [];
            $articleRepository = new ArticleRepository;
            $processedArticles = array_merge($processedArticles, $this->processArticles($articles['articles']));
            $articleRepository->bulkInsert($processedArticles);
        }

        return false;
    }

    private function processArticles(array $articles): array
    {
        $articlesDTO = [];
        foreach ($articles as $article) {
            if (isset($article['source']['name']) && $article['source']['name'] != '[Removed]') {
                $articlesDTO[] = NewsApiTransformer::transform($article, $this->newsSourceId, $this->categoryId);
            }
        }

        return $articlesDTO;
    }
}
