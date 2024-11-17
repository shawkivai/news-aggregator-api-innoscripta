<?php

namespace Tests\Unit\Domains\V1\Article\Services;

use App\Domain\V1\Article\Repositories\ArticleRepository;
use App\Domain\V1\Article\Repositories\NewsSourceRepository;
use App\Domain\V1\Article\Services\NewsAggregatorService;
use App\Domain\V1\Article\Services\NewsApiService;
use App\Domain\V1\Article\Services\NewYorkTimesApiService;
use App\Domain\V1\Article\Services\TheGuardianApiService;
use Tests\TestCase;

class NewsAggregatorServiceTest extends TestCase
{
    protected $newsAggregatorService;

    protected $newsSourceRepository;

    protected $articleRepository;

    protected $newsApiService;

    protected $theGuardianApiService;

    protected $newYorkTimesApiService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->newsAggregatorService = new NewsAggregatorService(
            $this->newsSourceRepository = $this->createMock(NewsSourceRepository::class),
            $this->articleRepository = $this->createMock(ArticleRepository::class),
            $this->newsApiService = $this->createMock(NewsApiService::class),
            $this->theGuardianApiService = $this->createMock(TheGuardianApiService::class),
            $this->newYorkTimesApiService = $this->createMock(NewYorkTimesApiService::class)
        );
    }

    public function test_find_and_store_articles_with_articles()
    {
        // Mocking the news sources and categories
        $this->newsSourceRepository->expects($this->once())
            ->method('all')
            ->willReturn(collect([
                (object)['name' => 'newsapi', 'api_key' => 'key1', 'base_url' => 'url1'],
                (object)['name' => 'the guardian', 'api_key' => 'key2', 'base_url' => 'url2'],
                (object)['name' => 'new york times', 'api_key' => 'key3', 'base_url' => 'url3'],
        ]));
        $this->newsSourceRepository->expects($this->once())
            ->method('newsCategories')
            ->willReturn(['category1', 'category2']);

        // Mocking the services to return articles
        $this->newsApiService->expects($this->once())
            ->method('getArticles')
            ->willReturn((['article1', 'article2']));
        $this->theGuardianApiService->expects($this->once())
            ->method('getArticles')
            ->willReturn((['article3']));
        $this->newYorkTimesApiService->expects($this->once())
            ->method('getArticles')
            ->willReturn((['article4']));

        // Expecting bulkInsert to be called with the merged articles
        $this->articleRepository->expects($this->once())
            ->method('bulkInsert')
            ->with(['article1', 'article2', 'article3', 'article4'])
            ->willReturn(true);

        $result = $this->newsAggregatorService->findAndStoreArticles();
        $this->assertTrue($result);
    }

}
