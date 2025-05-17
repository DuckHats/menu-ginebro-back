<?php

namespace App\Policies;

use App\Models\User;

class OrderPolicy
{
    public function create(User $user): bool
    {
        return $user->isAdmin();
    }

    public function update(User $user): bool
    {
        return $user->isAdmin();
    }

    public function updateStatus(User $user): bool
    {
        return $user->isAdmin() || $user->isCook();
    }

    public function delete(User $user): bool
    {
        return $user->isAdmin();
    }
}
