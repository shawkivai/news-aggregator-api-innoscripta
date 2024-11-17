<?php

namespace App\Domain\V1\Article\DTO;

use App\DataTransferObjects\AbstractDTO;

class NewsAPIDTO extends AbstractDTO
{
    public string $title;

    public string $description;

    public string $url;

    public string $published_at;

    public ?string $author;

    public ?string $content;

    public int $news_source_id;

    public function process(): array
    {
        return [
            'news_source_id' => $this->news_source_id,
            'title' => $this->title,
            'description' => $this->description,
            'url' => $this->url,
            'published_at' => $this->published_at,
            'author' => $this->author,
            'content' => $this->content,
        ];
    }
}
