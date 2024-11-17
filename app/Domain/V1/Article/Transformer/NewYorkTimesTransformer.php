<?php

namespace App\Domain\V1\Article\Transformer;

use Carbon\Carbon;

class NewYorkTimesTransformer
{
    public static function transform(array $article, int $newsSourceId): array
    {
        return [
            'news_source_id' => $newsSourceId,
            'title' => isset($article['headline']['main']) ? substr($article['headline']['main'], 0, 255) : substr($article['abstract'], 0, 255),
            'description' => $article['abstract'],
            'content' => $article['lead_paragraph'],
            'url' => $article['web_url'],
            'published_at' => Carbon::parse($article['pub_date'])->format('Y-m-d H:i:s'),
            'author' => preg_replace('/By /', '', $article['byline']['original']),
        ];
    }
}
