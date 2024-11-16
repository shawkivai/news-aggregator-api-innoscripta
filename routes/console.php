<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->everyMinute();

// Artisan::command('download:articles', function () {
//     $this->comment('Downloading articles from all news sources');
//     // $this->call('download:articles');
// })->purpose('Download articles from all news sources')->everyMinute();
