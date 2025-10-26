<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\NonUser;
use Illuminate\Auth\Access\HandlesAuthorization;

class NonUserPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:NonUser');
    }

    public function view(AuthUser $authUser, NonUser $nonUser): bool
    {
        return $authUser->can('View:NonUser');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:NonUser');
    }

    public function update(AuthUser $authUser, NonUser $nonUser): bool
    {
        return $authUser->can('Update:NonUser');
    }

    public function delete(AuthUser $authUser, NonUser $nonUser): bool
    {
        return $authUser->can('Delete:NonUser');
    }

    public function restore(AuthUser $authUser, NonUser $nonUser): bool
    {
        return $authUser->can('Restore:NonUser');
    }

    public function forceDelete(AuthUser $authUser, NonUser $nonUser): bool
    {
        return $authUser->can('ForceDelete:NonUser');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:NonUser');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:NonUser');
    }

    public function replicate(AuthUser $authUser, NonUser $nonUser): bool
    {
        return $authUser->can('Replicate:NonUser');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:NonUser');
    }

}