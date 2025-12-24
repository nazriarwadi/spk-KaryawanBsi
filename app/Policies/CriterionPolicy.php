<?php

namespace App\Policies;

use App\Models\Criterion;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class CriterionPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }
    public function view(User $user, Criterion $criterion): bool
    {
        return true;
    }
    public function create(User $user): bool
    {
        return !$user->isPimpinan();
    }
    public function update(User $user, Criterion $criterion): bool
    {
        return !$user->isPimpinan();
    }
    public function delete(User $user, Criterion $criterion): bool
    {
        return !$user->isPimpinan();
    }
}
