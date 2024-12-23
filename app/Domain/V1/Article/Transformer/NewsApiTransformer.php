<?php

namespace App\Domain\V1\Article\Transformer;

use App\Domain\V1\Article\DTO\NewsAggregatorDTO;
use Carbon\Carbon;

class NewsApiTransformer
{
    public static function transform(array $article, int $newsSourceId, int $categoryId): array
    {
        return (new NewsAggregatorDTO([
            'news_source_id' => $newsSourceId,
            'category_id' => $categoryId,
            'title' => substr($article['title'], 0, 255),
            'description' => trim($article['description']),
            'url' => $article['url'],
            'published_at' => Carbon::parse($article['publishedAt'])->format('Y-m-d H:i:s'),
            'author' => substr($article['author'], 0, 255),
            'content' => trim($article['content']),
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]))->process();
    }
}
