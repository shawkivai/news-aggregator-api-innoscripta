<?php

namespace App\Domain\V1\NewsSource\Transformer;

use App\Domain\V1\NewsSource\DTO\NewsAPIDTO;
use Carbon\Carbon;

class NewsApiTransformer
{
    public static function transform(array $article, int $newsSourceId): array
    {
        return (new NewsAPIDTO([
            'news_source_id' => $newsSourceId,
            'title' => substr($article['title'], 0, 255),
            'description' => trim($article['description']),
            'url' => $article['url'],
            'published_at' => Carbon::parse($article['publishedAt'])->format('Y-m-d H:i:s'),
            'author' => $article['author'],
            'content' => trim($article['content']),
        ]))->process();
    }
}
