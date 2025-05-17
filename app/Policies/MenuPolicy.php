<?php

namespace App\Policies;

use App\Models\User;

class MenuPolicy
{
    public function create(User $user): bool
    {
        return $user->isAdmin();
    }

    public function update(User $user): bool
    {
        return $user->isAdmin();
    }

    public function delete(User $user): bool
    {
        return $user->isAdmin();
    }

    public function getByDate (User $user): bool
    {
        return $user->isAdmin() || $user->isCook() || $user->isUser();
    }
}
