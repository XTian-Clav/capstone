<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\ReserveSupply;
use Illuminate\Auth\Access\HandlesAuthorization;

class ReserveSupplyPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:ReserveSupply');
    }

    public function view(AuthUser $authUser, ReserveSupply $reserveSupply): bool
    {
        return $authUser->can('View:ReserveSupply');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:ReserveSupply');
    }

    public function update(AuthUser $authUser, ReserveSupply $reserveSupply): bool
    {
        return $authUser->can('Update:ReserveSupply');
    }

    public function delete(AuthUser $authUser, ReserveSupply $reserveSupply): bool
    {
        return $authUser->can('Delete:ReserveSupply');
    }

    public function restore(AuthUser $authUser, ReserveSupply $reserveSupply): bool
    {
        return $authUser->can('Restore:ReserveSupply');
    }

    public function forceDelete(AuthUser $authUser, ReserveSupply $reserveSupply): bool
    {
        return $authUser->can('ForceDelete:ReserveSupply');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:ReserveSupply');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:ReserveSupply');
    }

    public function replicate(AuthUser $authUser, ReserveSupply $reserveSupply): bool
    {
        return $authUser->can('Replicate:ReserveSupply');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:ReserveSupply');
    }

}