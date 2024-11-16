<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    protected $table = 'articles';

    protected $fillable = [
        'news_source_id',
        'title',
        'description',
        'content',
        'url',
        'published_at',
        'author',
    ];
}
