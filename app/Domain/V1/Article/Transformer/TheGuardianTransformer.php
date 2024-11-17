<?php

namespace App\Domain\V1\Article\Transformer;

use Carbon\Carbon;

class TheGuardianTransformer
{
    public static function transform(array $article, int $newsSourceId, int $categoryId): array
    {
        return [
            'news_source_id' => $newsSourceId,
            'category_id' => $categoryId,
            'title' => substr($article['webTitle'], 0, 255),
            'description' => $article['webTitle'],
            'content' => null,
            'url' => $article['webUrl'],
            'published_at' => Carbon::parse($article['webPublicationDate'])->format('Y-m-d H:i:s'),
            'author' => $article['sectionName'],
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ];
    }
}
