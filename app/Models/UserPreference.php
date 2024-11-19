<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class UserPreference extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'status', 'preference_id', 'preference_type'];

    public function preference(): MorphTo
    {
        return $this->morphTo();
    }
}
