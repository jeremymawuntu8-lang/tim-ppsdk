<?php

namespace App\Policies;

use App\Models\BaWasAlse;
use App\Models\User;

class BaWasAlsePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('kelola-pengawasan') || $user->can('lihat-laporan');
    }

    public function view(User $user, BaWasAlse $baWasPrl): bool
    {
        return $this->viewAny($user);
    }

    public function create(User $user): bool
    {
        return $user->can('kelola-pengawasan');
    }

    public function update(User $user, BaWasAlse $baWasPrl): bool
    {
        return $user->can('kelola-pengawasan');
    }

    public function delete(User $user, BaWasAlse $baWasPrl): bool
    {
        return $user->hasRole(['super-admin', 'admin']) || $user->id === $baWasPrl->created_by;
    }
}
