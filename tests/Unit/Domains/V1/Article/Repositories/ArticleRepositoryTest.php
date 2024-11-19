<?php

namespace Tests\Unit\Domains\V1\Article\Repositories;

use App\Domain\V1\Article\Repositories\ArticleRepository;
use App\Enums\V1\StatusEnum;
use App\Models\Article;
use App\Models\Category;
use App\Models\NewsSource;
use App\Models\User;
use App\Models\UserPreference;
use Illuminate\Database\Eloquent\Collection;
use Tests\TestCase;

class ArticleRepositoryTest extends TestCase
{
    protected $articleRepository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->articleRepository = new ArticleRepository;
    }

    public function test_set_category_id()
    {
        $this->articleRepository->setCategoryId(1);
        $this->assertEquals(1, $this->articleRepository->getCategoryId());
    }

    public function test_set_source_id()
    {
        $this->articleRepository->setSourceId(1);
        $this->assertEquals(1, $this->articleRepository->getSourceId());
    }

    public function test_set_published_at()
    {
        $this->articleRepository->setPublishedAt(now());
        $this->assertEquals(now(), $this->articleRepository->getPublishedAt());
    }

    public function test_get_articles()
    {
        $this->articleRepository->setCategoryId(1)->setSourceId(1)->setOffset(0)->setLimit(10);
        $this->assertInstanceOf(Collection::class, $this->articleRepository->getArticles());
    }

    public function test_search_articles()
    {
        $this->articleRepository->setSearchKeyword('tesla')->setCategoryId(1)->setSourceId(1)->setPublishedAt(now())->setOffset(0)->setLimit(10);
        $this->assertInstanceOf(Collection::class, $this->articleRepository->searchArticles());
    }

    public function test_get_article_by_id()
    {
        $this->articleRepository->setArticleId(1);
        $this->assertInstanceOf(Article::class, $this->articleRepository->getArticleById());
    }

    public function test_bulk_insert_articles()
    {
        $this->assertTrue($this->articleRepository->bulkInsert([]));
    }

    public function test_get_articles_by_user_preferences()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $category = Category::factory()->create([
            'name' => 'Test Category',
            'status' => StatusEnum::ACTIVE->value,
        ]);
        $newsSource = NewsSource::factory()->create([
            'name' => 'Test News Source',
            'api_key' => 'test_api_key',
            'base_url' => 'https://test.com',
            'status' => StatusEnum::ACTIVE->value,
        ]);

        UserPreference::factory()->create([
            'user_id' => $user->id,
            'preference_type' => Category::class,
            'preference_id' => $category->id,
            'status' => StatusEnum::ACTIVE->value,
        ]);

        UserPreference::factory()->create([
            'user_id' => $user->id,
            'preference_type' => NewsSource::class,
            'preference_id' => $newsSource->id,
            'status' => StatusEnum::ACTIVE->value,
        ]);

        $article = Article::factory()->create([
            'category_id' => $category->id,
            'news_source_id' => $newsSource->id,
            'published_at' => now(),
            'title' => 'Test Article',
            'description' => 'Test Description',
            'url' => 'https://test.com',
            'content' => 'Test Content',
            'author' => 'Test Author',
        ]);

        $repository = new ArticleRepository;
        $repository->setOffset(0)->setLimit(10);

        // Act: Call the method
        $articles = $repository->getArticlesByUserPreferences();

        // Assert: Check if the returned articles match the expected results
        $this->assertCount(1, $articles);
        $this->assertEquals($article->id, $articles->first()->id);

    }
}
