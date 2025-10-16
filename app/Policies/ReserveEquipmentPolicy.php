<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\ReserveEquipment;
use Illuminate\Auth\Access\HandlesAuthorization;

class ReserveEquipmentPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:ReserveEquipment');
    }

    public function view(AuthUser $authUser, ReserveEquipment $reserveEquipment): bool
    {
        return $authUser->can('View:ReserveEquipment');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:ReserveEquipment');
    }

    public function update(AuthUser $authUser, ReserveEquipment $reserveEquipment): bool
    {
        return $authUser->can('Update:ReserveEquipment');
    }

    public function delete(AuthUser $authUser, ReserveEquipment $reserveEquipment): bool
    {
        return $authUser->can('Delete:ReserveEquipment');
    }

    public function restore(AuthUser $authUser, ReserveEquipment $reserveEquipment): bool
    {
        return $authUser->can('Restore:ReserveEquipment');
    }

    public function forceDelete(AuthUser $authUser, ReserveEquipment $reserveEquipment): bool
    {
        return $authUser->can('ForceDelete:ReserveEquipment');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:ReserveEquipment');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:ReserveEquipment');
    }

    public function replicate(AuthUser $authUser, ReserveEquipment $reserveEquipment): bool
    {
        return $authUser->can('Replicate:ReserveEquipment');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:ReserveEquipment');
    }

}