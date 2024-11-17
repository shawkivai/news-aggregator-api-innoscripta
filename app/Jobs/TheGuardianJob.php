<?php

namespace App\Jobs;

use App\Domain\V1\Article\Repositories\ArticleRepository;
use App\Domain\V1\Article\Transformer\TheGuardianTransformer;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Http;

class TheGuardianJob implements ShouldQueue
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
    public function handle()
    {
        $response = Http::get($this->url);

        if ($response->successful() && ! empty($response->json())) {
            $articles = $response->json();
            if (isset($articles['response']['results'])) {
                $processedArticles = $this->processArticles($articles['response']['results'], $this->newsSourceId, $this->categoryId);
                // dd($processedArticles);
                $articleRepository = new ArticleRepository;

                return $articleRepository->bulkInsert($processedArticles);
            }
        }
    }

    private function processArticles(array $articles, int $newsSourceId, int $categoryId): array
    {
        return array_map(function ($article) use ($newsSourceId, $categoryId) {
            return TheGuardianTransformer::transform($article, $newsSourceId, $categoryId);
        }, $articles);
    }
}
