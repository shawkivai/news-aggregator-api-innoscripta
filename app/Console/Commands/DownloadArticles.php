<?php

namespace App\Console\Commands;

use App\Domain\V1\NewsSource\Repositories\ArticleRepository;
use App\Domain\V1\NewsSource\Repositories\NewsSourceRepository;
use App\Domain\V1\NewsSource\Services\NewsAggregatorService;
use Illuminate\Console\Command;

class DownloadArticles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'download:articles';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Download articles from news sources';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $newsAggregatorService = new NewsAggregatorService(
            new NewsSourceRepository,
            new ArticleRepository
        );

        if ($newsAggregatorService->findAndStoreArticles()) {
            $this->info('Articles downloaded successfully from all news sources');
        } else {
            $this->error('Failed to download articles from all news sources');
        }
    }
}
