<?php

namespace Tests\Unit\Domains\V1\Article\Services;

use App\Domain\V1\Article\Repositories\ArticleRepository;
use App\Domain\V1\Article\Repositories\NewsSourceRepository;
use App\Domain\V1\Article\Services\NewsAggregatorService;
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
        );
    }
}
