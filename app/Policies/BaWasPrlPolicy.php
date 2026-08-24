<?php

namespace App\Policies;

use App\Models\BaWasPrl;
use App\Models\User;

class BaWasPrlPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('kelola-pengawasan') || $user->can('lihat-laporan');
    }

    public function view(User $user, BaWasPrl $baWasPrl): bool
    {
        return $this->viewAny($user);
    }

    public function create(User $user): bool
    {
        return $user->can('kelola-pengawasan');
    }

    public function update(User $user, BaWasPrl $baWasPrl): bool
    {
        return $user->can('kelola-pengawasan');
    }

    public function delete(User $user, BaWasPrl $baWasPrl): bool
    {
        return $user->hasRole(['super-admin', 'admin']) || $user->id === $baWasPrl->created_by;
    }
}
