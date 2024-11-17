<?php

namespace App\Jobs;

use App\Domain\V1\Article\Repositories\ArticleRepository;
use App\Domain\V1\Article\Transformer\NewYorkTimesTransformer;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Http;

class NewYorkTimesJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public string $url,
        public int $newsSourceId,
        public int $categoryId
    ) {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): bool
    {
        $response = Http::get($this->url);

        if ($response->successful()) {
            $articles = $response->json();
            if (! empty($articles['response']['docs'])) {
                $processedArticles = $this->processArticles($articles['response']['docs'], $this->newsSourceId, $this->categoryId);
                $articleRepository = new ArticleRepository;

                return $articleRepository->bulkInsert($processedArticles);
            }
        }

        return false;
    }

    private function processArticles(array $articles, int $newsSourceId, int $categoryId): array
    {
        return array_map(function ($article) use ($newsSourceId, $categoryId) {
            return NewYorkTimesTransformer::transform($article, $newsSourceId, $categoryId);
        }, $articles);
    }
}
