<?php

namespace Tests\Unit\Domains\V1\Article\Repositories;

use App\Domain\V1\Article\Repositories\NewsSourceRepository;
use Illuminate\Database\Eloquent\Collection;
use Tests\TestCase;

class NewsSourceRepositoryTest extends TestCase
{
    protected $newsSourceRepository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->newsSourceRepository = new NewsSourceRepository;
    }

    public function test_get_all_news_sources()
    {
        $result = $this->newsSourceRepository->all();
        $this->assertInstanceOf(Collection::class, $result);
    }

    public function test_get_all_news_categories()
    {
        $result = $this->newsSourceRepository->newsCategories();
        $this->assertInstanceOf(Collection::class, $result);
    }
}
