<?php

namespace App\Domain\V1\Article\Transformer;

use App\Domain\V1\Article\DTO\ArticleDTO;

class ArticleTransformer
{
    public static function transform($article)
    {

        return (new ArticleDTO([
            'id' => $article->id,
            'title' => $article->title,
            'description' => $article->description,
            'url' => $article->url,
            'published_at' => $article->published_at,
            'author' => $article->author,
            'content' => $article->content,
            'category' => $article->category->name ?? null,
            'source' => $article->source->name ?? null,
        ]))->process();
    }
}
