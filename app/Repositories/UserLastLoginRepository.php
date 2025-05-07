<?php

namespace App\Repositories;

use App\Models\UserLastLogin;

class UserLastLoginRepository
{
    public function create(array $data): UserLastLogin
    {
        return UserLastLogin::create($data);
    }

    public function existsForIP(int $userId, string $ipAddress): bool
    {
        return UserLastLogin::where('user_id', $userId)
            ->where('ip_address', $ipAddress)
            ->exists();
    }
}
