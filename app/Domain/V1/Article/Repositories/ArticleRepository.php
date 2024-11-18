<?php

namespace App\Domain\V1\Article\Repositories;

use App\Models\Article;
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

    public function bulkInsert(array $articles): bool
    {
        return Article::insert($articles);
    }

    public function setCategoryId($categoryId): self
    {
        $this->categoryId = $categoryId;

        return $this;
    }

    public function setSourceId($sourceId): self
    {
        $this->sourceId = $sourceId;

        return $this;
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

    public function setSearchKeyword($keyword): self
    {
        $this->searchKeyword = $keyword;

        return $this;
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
}
