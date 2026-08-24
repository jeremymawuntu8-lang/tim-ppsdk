<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('kelola-user');
    }

    public function create(User $user): bool
    {
        return $user->can('kelola-user');
    }

    public function update(User $user, User $target): bool
    {
        if (! $user->can('kelola-user')) {
            return false;
        }

        // Hanya super-admin yang boleh mengubah akun super-admin lain.
        return $user->hasRole('super-admin') || ! $target->hasRole('super-admin');
    }

    public function delete(User $user, User $target): bool
    {
        return $user->can('kelola-user') && $user->id !== $target->id && ! $target->hasRole('super-admin');
    }
}
