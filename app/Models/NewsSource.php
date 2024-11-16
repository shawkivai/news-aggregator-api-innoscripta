<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NewsSource extends Model
{
    protected $table = 'news_sources';

    protected $fillable = [
        'name',
        'api_key',
        'url',
        'status',
    ];
}
