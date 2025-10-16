<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\Supply;
use Illuminate\Auth\Access\HandlesAuthorization;

class SupplyPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:Supply');
    }

    public function view(AuthUser $authUser, Supply $supply): bool
    {
        return $authUser->can('View:Supply');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:Supply');
    }

    public function update(AuthUser $authUser, Supply $supply): bool
    {
        return $authUser->can('Update:Supply');
    }

    public function delete(AuthUser $authUser, Supply $supply): bool
    {
        return $authUser->can('Delete:Supply');
    }

    public function restore(AuthUser $authUser, Supply $supply): bool
    {
        return $authUser->can('Restore:Supply');
    }

    public function forceDelete(AuthUser $authUser, Supply $supply): bool
    {
        return $authUser->can('ForceDelete:Supply');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:Supply');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:Supply');
    }

    public function replicate(AuthUser $authUser, Supply $supply): bool
    {
        return $authUser->can('Replicate:Supply');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:Supply');
    }

}