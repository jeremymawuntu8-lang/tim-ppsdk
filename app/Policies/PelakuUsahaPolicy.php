<?php

namespace App\Policies;

use App\Models\PelakuUsaha;
use App\Models\User;

class PelakuUsahaPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('kelola-master-data') || $user->can('lihat-map') || $user->can('lihat-laporan');
    }

    public function view(User $user, PelakuUsaha $pelakuUsaha): bool
    {
        return $this->viewAny($user);
    }

    public function create(User $user): bool
    {
        return $user->can('kelola-master-data');
    }

    public function update(User $user, PelakuUsaha $pelakuUsaha): bool
    {
        return $user->can('kelola-master-data');
    }

    public function delete(User $user, PelakuUsaha $pelakuUsaha): bool
    {
        return $user->hasRole(['super-admin', 'admin']);
    }
}
