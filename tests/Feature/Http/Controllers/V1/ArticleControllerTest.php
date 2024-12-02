<?php

namespace Tests\Feature\Http\Controllers\V1;

use App\DataTransferObjects\ServiceResponseDTO;
use App\Domain\V1\Article\Services\ArticleService;
use App\Enums\V1\HttpStatus;
use App\Http\Controllers\V1\ArticleController;
use App\Http\Requests\ArticleSearchRequest;
use Illuminate\Http\Request;
use Tests\TestCase;

class ArticleControllerTest extends TestCase
{
    protected $articleController;

    protected $articleService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->articleController = new ArticleController(
            $this->articleService = $this->createMock(ArticleService::class),
        );
    }

    public function test_index()
    {
        $this->articleService->expects($this->once())
            ->method('getArticles')
            ->willReturn(new ServiceResponseDTO([
                'status' => HttpStatus::SUCCESS_RESPONSE,
                'httpStatusCode' => HttpStatus::SUCCESS,
                'responseMessage' => 'Articles fetched successfully',
                'response' => collect([]),
                'headers' => [],
            ]));

        $response = $this->articleController->index();
        $this->assertEquals(HttpStatus::SUCCESS, $response->getStatusCode());
    }

    public function test_search_articles()
    {
        $this->articleService->expects($this->once())
            ->method('searchArticles')
            ->willReturn(new ServiceResponseDTO([
                'status' => HttpStatus::SUCCESS_RESPONSE,
                'httpStatusCode' => HttpStatus::SUCCESS,
                'responseMessage' => 'Articles fetched successfully',
                'response' => collect([]),
                'headers' => [],
            ]));

        $response = $this->articleController->search(new ArticleSearchRequest(['keyword' => 'tesla', 'date' => '2022-01-01', 'category' => 1, 'source' => 1]));
        $this->assertEquals(HttpStatus::SUCCESS, $response->getStatusCode());
    }

    public function test_view_article_details()
    {
        $this->articleService->expects($this->once())
            ->method('getArticleDetails')
            ->willReturn(new ServiceResponseDTO([
                'status' => HttpStatus::SUCCESS_RESPONSE,
                'httpStatusCode' => HttpStatus::SUCCESS,
                'responseMessage' => 'Article details fetched successfully',
                'response' => collect([]),
                'headers' => [],
            ]));

        $response = $this->articleController->view(1);
        $this->assertEquals(HttpStatus::SUCCESS, $response->getStatusCode());
    }
}
