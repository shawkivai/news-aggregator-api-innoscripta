<?php

namespace Tests\Unit\Domains\V1\Article\Services;

use App\Domain\V1\Article\Services\NewYorkTimesApiService;
use App\Jobs\NewYorkTimesJob;
use Illuminate\Support\Facades\Bus;
use Tests\TestCase;

class NewYorkTimesApiServiceTest extends TestCase
{
    protected $newYorkTimesApiService;

    public function setUp(): void
    {
        parent::setUp();
        $this->newYorkTimesApiService = new NewYorkTimesApiService;
    }

    public function test_set_base_url()
    {
        $baseUrl = 'https://api.nytimes.com/svc/search/v2/articlesearch.json';
        $this->newYorkTimesApiService->setBaseUrl($baseUrl);
        $this->assertEquals($baseUrl, $this->newYorkTimesApiService->getBaseUrl());
    }

    public function test_set_api_key()
    {
        $apiKey = 'api_key';
        $this->newYorkTimesApiService->setApiKey($apiKey);
        $this->assertEquals($apiKey, $this->newYorkTimesApiService->getApiKey());
    }

    public function test_set_query_params()
    {
        $searchTerms = 'tesla';
        $apiKey = 'api_key';
        $queryParams = 'q='.$searchTerms.'&sort=newest&page=1&api-key='.$apiKey;
        $this->newYorkTimesApiService->setApiKey($apiKey)->setQueryParams($searchTerms);
        $this->assertEquals($queryParams, $this->newYorkTimesApiService->getQueryParams());
    }

    // public function test_get_articles_dispatches_job()
    // {
    //     // Mock the job dispatching
    //     Bus::fake();

    //     // Parameters
    //     $newsSourceId = 1;
    //     $categoryId = 2;
    //     $baseUrl = 'https://api.nytimes.com/svc/search/v2/articlesearch.json';
    //     $queryParams = 'q=tesla&sort=newest&page=1&api-key=api_key';

    //     // Call the method
    //     $this->newYorkTimesApiService->setBaseUrl($baseUrl)->setApiKey('api_key')->setQueryParams($queryParams)->getArticles($newsSourceId, $categoryId);

    //     // Mock the job to be dispatched with the correct arguments
    //     $mockJob = $this->createMock(NewYorkTimesJob::class);
    //     $mockJob->expects($this->once())
    //         ->method('handle')
    //         ->with($this->equalTo($baseUrl . '?' . $queryParams), $this->equalTo($newsSourceId), $this->equalTo($categoryId));

    //     Bus::assertDispatchedSync(NewYorkTimesJob::class, function ($job) use ($mockJob) {
    //         return $mockJob;
    //     });

    // }
}
