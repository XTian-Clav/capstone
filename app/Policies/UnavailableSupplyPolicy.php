<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\UnavailableSupply;
use Illuminate\Auth\Access\HandlesAuthorization;

class UnavailableSupplyPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:UnavailableSupply');
    }

    public function view(AuthUser $authUser, UnavailableSupply $unavailableSupply): bool
    {
        return $authUser->can('View:UnavailableSupply');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:UnavailableSupply');
    }

    public function update(AuthUser $authUser, UnavailableSupply $unavailableSupply): bool
    {
        return $authUser->can('Update:UnavailableSupply');
    }

    public function delete(AuthUser $authUser, UnavailableSupply $unavailableSupply): bool
    {
        return $authUser->can('Delete:UnavailableSupply');
    }

    public function restore(AuthUser $authUser, UnavailableSupply $unavailableSupply): bool
    {
        return $authUser->can('Restore:UnavailableSupply');
    }

    public function forceDelete(AuthUser $authUser, UnavailableSupply $unavailableSupply): bool
    {
        return $authUser->can('ForceDelete:UnavailableSupply');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:UnavailableSupply');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:UnavailableSupply');
    }

    public function replicate(AuthUser $authUser, UnavailableSupply $unavailableSupply): bool
    {
        return $authUser->can('Replicate:UnavailableSupply');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:UnavailableSupply');
    }

}