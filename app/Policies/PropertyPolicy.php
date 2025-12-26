<?php

namespace App\Policies;

use App\Models\User;
use App\Enums\UserRole;
use App\Models\Property;
use Illuminate\Auth\Access\Response;

class PropertyPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // return true;
        return $user->can('properties.view');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Property $property): bool
    {
        // return $user->isAdmin() || $property->owner_id === $user->id;
        // return $user->hasRole(UserRole::SUPER_ADMIN)
        //     || $property->owner_id === $user->id;

            return $user->hasAnyRole([UserRole::ADMIN->value, UserRole::SUPER_ADMIN->value])|| $property->owner_id === $user->id;
            // return $user->hasRole('super_admin')
            //     || ($user->can('properties.view') && $property->owner_id === $user->id);
            // return true;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return in_array($user->role, [
            UserRole::SUPER_ADMIN,
            UserRole::PROPERTY_OWNER,
        ], true);
        // return $user->can('properties.create');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Property $property): bool
    {
        // return $user->isSuperAdmin() || $property->user_id === $user->id;
        // return $this->view($user, $property);
        return $user->hasRole('admin')
            || ($user->can('properties.update') && $property->owner_id === $user->id);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Property $property): bool
    {
        // return $user->isSuperAdmin();
        return $user->hasRole('admin');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Property $property): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Property $property): bool
    {
        return false;
    }
}
