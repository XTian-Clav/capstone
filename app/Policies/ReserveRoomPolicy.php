<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\ReserveRoom;
use Illuminate\Auth\Access\HandlesAuthorization;

class ReserveRoomPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:ReserveRoom');
    }

    public function view(AuthUser $authUser, ReserveRoom $reserveRoom): bool
    {
        return $authUser->can('View:ReserveRoom');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:ReserveRoom');
    }

    public function update(AuthUser $authUser, ReserveRoom $reserveRoom): bool
    {
        return $authUser->can('Update:ReserveRoom');
    }

    public function delete(AuthUser $authUser, ReserveRoom $reserveRoom): bool
    {
        return $authUser->can('Delete:ReserveRoom');
    }

    public function restore(AuthUser $authUser, ReserveRoom $reserveRoom): bool
    {
        return $authUser->can('Restore:ReserveRoom');
    }

    public function forceDelete(AuthUser $authUser, ReserveRoom $reserveRoom): bool
    {
        return $authUser->can('ForceDelete:ReserveRoom');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:ReserveRoom');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:ReserveRoom');
    }

    public function replicate(AuthUser $authUser, ReserveRoom $reserveRoom): bool
    {
        return $authUser->can('Replicate:ReserveRoom');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:ReserveRoom');
    }

}