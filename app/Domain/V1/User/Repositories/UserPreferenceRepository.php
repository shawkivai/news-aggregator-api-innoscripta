<?php

namespace App\Domain\V1\User\Repositories;

use App\Models\UserPreference;
use Illuminate\Container\Attributes\Auth;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class UserPreferenceRepository
{
    protected int $user_id;
    protected int $preference_id;
    protected string $preference_type;

    public function setUserId(int $userId): self
    {
        $this->user_id = $userId;

        return $this;
    }

    public function setPreference(Model $preference): self
    {
        $this->preference_id = $preference->id;
        $this->preference_type = $preference->getMorphClass();

        return $this;
    }

    // public function saveOrUpdate(): UserPreference
    // {
    //     return UserPreference::userPreferences()->updateOrCreate(
    //         ['user_id' => $this->user_id, 'preference_id' => $this->preference_id],
    //         [
    //             'preference_type' => $this->preference_type,
    //         ]
    //     );
    // }

    public function getUserPreferences(): Collection
    {
        return UserPreference::where('user_id', $this->user_id)
            ->select('id', 'preference_id', 'preference_type')
            ->with(['preference' => function ($query) {
                $query->select('id', 'name');
            }])
            ->get();
    }
}
