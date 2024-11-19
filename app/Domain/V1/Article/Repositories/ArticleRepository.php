<?php

namespace App\Domain\V1\Article\Repositories;

use App\Enums\V1\StatusEnum;
use App\Models\Article;
use App\Models\Category;
use App\Models\NewsSource;
use App\Models\UserPreference;
use Illuminate\Database\Eloquent\Collection;

class ArticleRepository
{
    protected $categoryId;

    protected $sourceId;

    protected $limit;

    protected $offset;

    protected $searchKeyword;

    protected $publishedAt;

    protected $newsSourceId;

    protected $articleId;

    public function setArticleId($articleId): self
    {
        $this->articleId = $articleId;

        return $this;
    }

    public function setCategoryId($categoryId): self
    {
        $this->categoryId = $categoryId;

        return $this;
    }

    public function getCategoryId(): int
    {
        return $this->categoryId;
    }

    public function setSourceId($sourceId): self
    {
        $this->sourceId = $sourceId;

        return $this;
    }

    public function getSourceId(): int
    {
        return $this->sourceId;
    }

    public function setLimit($limit = 20): self
    {
        $this->limit = $limit;

        return $this;
    }

    public function setOffset($offset = 0): self
    {
        $this->offset = $offset;

        return $this;
    }

    public function setPublishedAt($date): self
    {
        $this->publishedAt = $date;

        return $this;
    }

    public function getPublishedAt(): string
    {
        return $this->publishedAt;
    }

    public function setSearchKeyword($keyword): self
    {
        $this->searchKeyword = $keyword;

        return $this;
    }

    public function bulkInsert(array $articles): bool
    {
        return Article::insert($articles);
    }

    public function getArticles(): Collection
    {
        return Article::query()
            ->with('category', 'source')
            ->when($this->categoryId, function ($query) {
                $query->where('category_id', $this->categoryId);
            })
            ->when($this->sourceId, function ($query) {
                $query->where('news_source_id', $this->sourceId);
            })
            ->orderBy('published_at', 'desc')
            ->offset($this->offset)
            ->limit($this->limit)
            ->get();

    }

    public function searchArticles(): Collection
    {
        return Article::query()
            ->when($this->searchKeyword, function ($query) {
                $query->whereRaw('MATCH(title, content, description) AGAINST(? IN BOOLEAN MODE)', [$this->searchKeyword]);
            })
            ->when($this->publishedAt, function ($query) {
                $query->whereDate('published_at', $this->publishedAt);
            })
            ->when($this->categoryId, function ($query) {
                $query->where('category_id', $this->categoryId);
            })
            ->when($this->sourceId, function ($query) {
                $query->where('news_source_id', $this->sourceId);
            })
            ->orderBy('published_at', 'desc')
            ->offset($this->offset)
            ->limit($this->limit)
            ->get();
    }

    public function getArticleById(): Article
    {
        return Article::with('category', 'source')->findOrFail($this->articleId);
    }

    public function getArticlesByUserPreferences(): Collection
    {
        $userPreferences = UserPreference::where(['user_id' => auth()->id(), 'status' => StatusEnum::ACTIVE->value])->get();
        $categoryIds = $userPreferences->where('preference_type', Category::class)->pluck('preference_id')->toArray();
        $newsSourceIds = $userPreferences->where('preference_type', NewsSource::class)->pluck('preference_id')->toArray();

        return Article::query()
            ->when($categoryIds, function ($query) use ($categoryIds) {
                $query->whereIn('category_id', $categoryIds);
            })
            ->when($newsSourceIds, function ($query) use ($newsSourceIds) {
                $query->whereIn('news_source_id', $newsSourceIds);
            })
            ->orderBy('published_at', 'desc')
            ->offset($this->offset)
            ->limit($this->limit)
            ->inRandomOrder()
            ->get();
    }
}
