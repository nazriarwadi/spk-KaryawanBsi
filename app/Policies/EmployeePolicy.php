<?php

namespace App\Policies;

use App\Models\Employee;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class EmployeePolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }
    public function view(User $user, Employee $employee): bool
    {
        return true;
    }
    public function create(User $user): bool
    {
        return !$user->isPimpinan();
    }
    public function update(User $user, Employee $employee): bool
    {
        return !$user->isPimpinan();
    }
    public function delete(User $user, Employee $employee): bool
    {
        return !$user->isPimpinan();
    }
}
