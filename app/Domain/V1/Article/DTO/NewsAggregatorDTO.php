<?php

namespace App\Domain\V1\Article\DTO;

use App\DataTransferObjects\AbstractDTO;

class NewsAggregatorDTO extends AbstractDTO
{
    public string $title;

    public string $description;

    public string $url;

    public string $published_at;

    public ?string $author;

    public ?string $content;

    public int $news_source_id;

    public int $category_id;

    public string $created_at;

    public function process(): array
    {
        return [
            'news_source_id' => $this->news_source_id,
            'category_id' => $this->category_id,
            'title' => $this->title,
            'description' => $this->description,
            'url' => $this->url,
            'published_at' => $this->published_at,
            'author' => $this->author,
            'content' => $this->content,
            'created_at' => $this->created_at,
        ];
    }
}
