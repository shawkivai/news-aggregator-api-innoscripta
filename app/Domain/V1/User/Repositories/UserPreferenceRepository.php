<?php

namespace App\Domain\V1\User\Repositories;

use App\Enums\V1\StatusEnum;
use App\Models\UserPreference;
use Illuminate\Database\Eloquent\Collection;

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

    public function getUserPreferences(): Collection
    {
        return UserPreference::where(['user_id' => $this->user_id, 'status' => StatusEnum::ACTIVE])
            ->select('id', 'preference_id', 'preference_type')
            ->with(['preference' => function ($query) {
                $query->select('id', 'name');
            }])
            ->get();
    }
}
