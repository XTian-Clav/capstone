<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\UnavailableEquipment;
use Illuminate\Auth\Access\HandlesAuthorization;

class UnavailableEquipmentPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:UnavailableEquipment');
    }

    public function view(AuthUser $authUser, UnavailableEquipment $unavailableEquipment): bool
    {
        return $authUser->can('View:UnavailableEquipment');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:UnavailableEquipment');
    }

    public function update(AuthUser $authUser, UnavailableEquipment $unavailableEquipment): bool
    {
        return $authUser->can('Update:UnavailableEquipment');
    }

    public function delete(AuthUser $authUser, UnavailableEquipment $unavailableEquipment): bool
    {
        return $authUser->can('Delete:UnavailableEquipment');
    }

    public function restore(AuthUser $authUser, UnavailableEquipment $unavailableEquipment): bool
    {
        return $authUser->can('Restore:UnavailableEquipment');
    }

    public function forceDelete(AuthUser $authUser, UnavailableEquipment $unavailableEquipment): bool
    {
        return $authUser->can('ForceDelete:UnavailableEquipment');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:UnavailableEquipment');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:UnavailableEquipment');
    }

    public function replicate(AuthUser $authUser, UnavailableEquipment $unavailableEquipment): bool
    {
        return $authUser->can('Replicate:UnavailableEquipment');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:UnavailableEquipment');
    }

}