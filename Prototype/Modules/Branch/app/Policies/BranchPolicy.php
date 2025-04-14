<?php

namespace Modules\Branch\app\Policies;

use Modules\Branch\app\Models\Branch;
use App\Models\User;

class BranchPolicy
{

    /**
     * Determine if the user can update or delete the model.
     */
    public function modify(User $user, Branch $branch): bool
    {
        // Check if the user is the owner OR has permission
        return $user->id === $branch->user_id || $user->hasPermissionTo('modify post');
    }

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Branch $branch): bool
    {
        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Branch $branch): bool
    {
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Branch $branch): bool
    {
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Branch $branch): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Branch $branch): bool
    {
        return false;
    }
}
