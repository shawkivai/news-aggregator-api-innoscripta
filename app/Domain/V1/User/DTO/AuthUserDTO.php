<?php

namespace App\Domain\V1\User\DTO;

use App\DataTransferObjects\AbstractDTO;

class AuthUserDTO extends AbstractDTO
{
    public int $id;

    public string $name;

    public string $email;

    public string $access_token;

    public string $refresh_token;

    public function process(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'access_token' => $this->access_token,
            'refresh_token' => $this->refresh_token,
        ];
    }
}
