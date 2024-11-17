<?php

namespace Tests\Unit\Domains\V1\Article\Services;

use App\Domain\V1\Article\Services\NewsApiService;
use App\Domain\V1\Article\Transformer\NewsApiTransformer;
use Illuminate\Support\Facades\Http;
use Mockery;
use Tests\TestCase;

class NewsApiServiceTest extends TestCase
{
    protected NewsApiService $newsApiService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->newsApiService = new NewsApiService();
    }

    // public function test_get_articles_returns_processed_articles(): void
    // {
    //     $this->newsApiService
    //         ->setApiKey('test-api-key')
    //         ->setBaseUrl('https://newsapi.org/v2/everything')
    //         ->setQueryParams(['keyword1', 'keyword2']);

    //     $responseArticles = [
    //         'articles' => [
    //             ['source' => ['name' => 'Source1'], 'title' => 'Article 1', 'description' => 'Description 1', 'url' => 'https://example.com/article1', 'content' => 'Content 1', 'publishedAt' => '2024-01-01', 'author' => 'Author 1'],
    //             ['source' => ['name' => 'Source2'], 'title' => 'Article 2', 'description' => 'Description 2', 'url' => 'https://example.com/article2', 'content' => 'Content 2', 'publishedAt' => '2024-01-02', 'author' => 'Author 2'],
    //             ['source' => ['name' => 'Source3'], 'title' => 'Article 3', 'description' => 'Description 3', 'url' => 'https://example.com/article3', 'content' => 'Content 3', 'publishedAt' => '2024-01-03', 'author' => 'Author 3'],
    //         ],
    //     ];

    //     Http::fake([
    //         '*' => Http::response($responseArticles, 200),
    //     ]);

    //     $newsSourceId = 1;

    //     $transformerMock = Mockery::mock(NewsApiTransformer::class);
    //     // Mock the NewsApiTransformer to ensure it processes articles correctly
    //     $transformerMock->shouldReceive('transform')
    //         ->with($responseArticles['articles'][0], $newsSourceId)
    //         ->andReturn(['processed_article_1'])
    //         ->times(1);

    //     $transformerMock->shouldReceive('transform')
    //         ->with($responseArticles['articles'][2], $newsSourceId)
    //         ->andReturn(['processed_article_3'])
    //         ->times(1);
        

    //     $result = $this->newsApiService->getArticles($newsSourceId);

    //     // dd($result);

    //     $this->assertEquals(
    //         [
    //             ['processed_article_1'],
    //             ['processed_article_3'],
    //         ],
    //         $result
    //     );
    // }
    
}
