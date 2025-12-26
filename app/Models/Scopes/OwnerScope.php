<?php

namespace App\Models\Scopes;

use App\Enums\UserRole;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class OwnerScope implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     */
    public function apply(Builder $builder, Model $model): void
    {
        if (
            auth()->check() &&
            auth()->user()->role === UserRole::PROPERTY_OWNER
        ) {
            $builder->whereHas('apartment.property', function ($q) {
                $q->where('owner_id', auth()->id());
            });
        }
    }
}
