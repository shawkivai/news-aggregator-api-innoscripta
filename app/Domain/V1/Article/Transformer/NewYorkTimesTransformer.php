<?php

namespace App\Domain\V1\Article\Transformer;

use App\Domain\V1\Article\DTO\NewsAggregatorDTO;
use Carbon\Carbon;

class NewYorkTimesTransformer
{
    public static function transform(array $article, int $newsSourceId, int $categoryId): array
    {
        return (new NewsAggregatorDTO([
            'news_source_id' => $newsSourceId,
            'category_id' => $categoryId,
            'title' => isset($article['headline']['main']) ? substr($article['headline']['main'], 0, 255) : substr($article['abstract'], 0, 255),
            'description' => $article['abstract'],
            'content' => $article['lead_paragraph'],
            'url' => $article['web_url'],
            'published_at' => Carbon::parse($article['pub_date'])->format('Y-m-d H:i:s'),
            'author' => preg_replace('/By /', '', $article['byline']['original']),
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]))->process();
    }
}
