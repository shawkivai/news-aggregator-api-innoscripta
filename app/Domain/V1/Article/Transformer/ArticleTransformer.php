<?php

namespace App\Domain\V1\Article\Transformer;

use App\Domain\V1\Article\DTO\ArticleDTO;
use App\Models\Article;
use Illuminate\Database\Eloquent\Collection;

class ArticleTransformer
{
    public static function transform($articles)
    {
        if (! ($articles instanceof Collection || $articles instanceof Article)) {
            throw new \InvalidArgumentException('Expected an instance of Collection or Article');
        }

        if ($articles instanceof Collection) {
            return $articles->map(function ($article) {
                return self::transformArticle($article);
            });
        } else {
            return self::transformArticle($articles);
        }
    }

    private static function transformArticle(Article $article)
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
