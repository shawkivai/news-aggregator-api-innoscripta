<?php

namespace Database\Seeders;

use App\Models\NewsSource;
use Illuminate\Database\Seeder;

class NewsSourceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $newsSources = [
            ['id' => 1, 'name' => 'NewsAPI', 'base_url' => 'https://newsapi.org/v2/everything', 'api_key' => '654d400cc7084f07b6f2e5cd5d7ec592', 'status' => true, 'created_at' => now()],
            ['id' => 2, 'name' => 'The Guardian', 'base_url' => 'https://content.guardianapis.com/search', 'api_key' => '4e6bbb6e-1bbd-4089-b508-5e8f5e2b715a', 'status' => true, 'created_at' => now()],
            ['id' => 3, 'name' => 'New York Times', 'base_url' => 'https://api.nytimes.com/svc/search/v2/articlesearch.json', 'api_key' => 'x22yxRZmxcsAXbDFEAkEGpIVXNCxFCZI', 'status' => true, 'created_at' => now()],
        ];

        foreach ($newsSources as $newsSource) {
            NewsSource::updateOrCreate(['id' => $newsSource['id']], $newsSource);
        }
    }
}
