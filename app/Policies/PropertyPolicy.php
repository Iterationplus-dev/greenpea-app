<?php

namespace App\Policies;

use App\Models\Property;
use App\Models\User;
use App\Models\Admin;

class PropertyPolicy
{
    /**
     * Who can see the property list
     */
    public function viewAny($actor): bool
    {
        // Admin panel
        if ($actor instanceof Admin) {
            return $actor->canManageProperties();
        }

        // Frontend users (owners see their own)
        if ($actor instanceof User) {
            return true;
        }

        return false;
    }

    /**
     * Who can view a property
     */
    public function view($actor, Property $property): bool
    {
        if ($actor instanceof Admin) {
            return $actor->canManageProperties();
        }

        if ($actor instanceof User) {
            return $property->owner_id === $actor->id;
        }

        return false;
    }

    /**
     * Who can create properties
     */
    public function create($actor): bool
    {
        if ($actor instanceof Admin) {
            return $actor->canManageProperties();
        }

        if ($actor instanceof User) {
            return $actor->isOwner();
        }

        return false;
    }

    /**
     * Who can update properties
     */
    public function update($actor, Property $property): bool
    {
        if ($actor instanceof Admin) {
            return $actor->canManageProperties();
        }

        if ($actor instanceof User) {
            return $property->owner_id === $actor->id;
        }

        return false;
    }

    /**
     * Only admins can delete properties
     */
    public function delete($actor, Property $property): bool
    {
        if ($actor instanceof Admin) {
            return $actor->isSuper();
        }

        return false;
    }

    public function restore($actor, Property $property): bool
    {
        return false;
    }

    public function forceDelete($actor, Property $property): bool
    {
        return false;
    }
}
