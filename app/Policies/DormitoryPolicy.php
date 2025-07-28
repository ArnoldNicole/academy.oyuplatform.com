<?php

namespace App\Policies;

use App\Models\Dormitory;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class DormitoryPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('dormitory-list');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Dormitory $dormitory): bool
    {
        return $user->can('dormitory-list') && $user->school_id === $dormitory->school_id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('dormitory-create');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Dormitory $dormitory): bool
    {
        return $user->can('dormitory-edit') && $user->school_id === $dormitory->school_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Dormitory $dormitory): bool
    {
        return $user->can('dormitory-delete') && $user->school_id === $dormitory->school_id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Dormitory $dormitory): bool
    {
        return $user->can('dormitory-edit') && $user->school_id === $dormitory->school_id;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Dormitory $dormitory): bool
    {
        return $user->can('dormitory-delete') && $user->school_id === $dormitory->school_id;
    }
}
