<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    /**
     * Siapa yang boleh melihat daftar User di menu sidebar?
     */
    public function viewAny(User $user): bool
    {
        // Admin ATAU HRD boleh melihat menu ini
        return $user->isAdmin() || $user->isHrd();
    }

    /**
     * Siapa yang boleh membuat user baru?
     */
    public function create(User $user): bool
    {
        // Admin ATAU HRD
        return $user->isAdmin() || $user->isHrd();
    }

    /**
     * Siapa yang boleh mengedit user?
     */
    public function update(User $user, User $model): bool
    {
        // Admin ATAU HRD
        return $user->isAdmin() || $user->isHrd();
    }

    /**
     * Siapa yang boleh menghapus user?
     */
    public function delete(User $user, User $model): bool
    {
        // Admin ATAU HRD
        return $user->isAdmin() || $user->isHrd();
    }
}
