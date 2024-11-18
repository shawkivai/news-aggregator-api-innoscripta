<?php

namespace App\Domain\V1\User\Transformer;

use App\Models\Category;

class UserPreferenceTransformer
{
    public static function transform($data)
    {
        $preferences = [];
        $data->each(function ($item) use (&$preferences) {
            $key = $item->preference_type == Category::class ? 'categories' : 'sources';
            $preferences[$key][] = [
                'id' => $item->preference->id,
                'name' => $item->preference->name,
            ];
        });

        return $preferences;
    }
}
