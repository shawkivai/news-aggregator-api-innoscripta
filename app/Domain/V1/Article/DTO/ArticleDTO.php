<?php

namespace App\Domain\V1\Article\DTO;

use App\DataTransferObjects\AbstractDTO;

class ArticleDTO extends AbstractDTO
{
    public int $id;

    public string $title;

    public string $description;

    public string $url;

    public string $published_at;

    public ?string $author;

    public ?string $content;

    public string $category;

    public ?string $source;

    public function process(): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'url' => $this->url,
            'published_at' => $this->published_at,
            'author' => $this->author,
            'content' => $this->content,
            'category' => $this->category,
            'source' => $this->source,
        ];
    }
}
