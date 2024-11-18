<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class NewsSource extends Model
{
    protected $table = 'news_sources';

    protected $fillable = [
        'name',
        'api_key',
        'url',
        'status',
    ];

    public function userPreferences(): MorphMany
    {
        return $this->morphMany(UserPreference::class, 'preference');
    }
}
