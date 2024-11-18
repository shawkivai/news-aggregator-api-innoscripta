<?php

namespace App\Domain\V1\Article\Transformer;

use App\Domain\V1\Article\DTO\ArticleDTO;
use App\Models\Article;
use Illuminate\Database\Eloquent\Collection;

class ArticleTransformer
{
    public static function transform(Collection $articles)
    {
        return $articles->map(function ($article) {
            if (! $article instanceof Article) {
                throw new \InvalidArgumentException('Expected an instance of Article');
            }

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
        });
    }
}
