<?php

namespace App\Contacts\V1;

interface NewsSourceInterface
{
    public function setApiKey(string $apiKey): self;

    public function setBaseUrl(string $baseUrl): self;

    public function setQueryParams(array|string $queryParams): self;

    public function getArticles(int $newsSourceId): array;
}
