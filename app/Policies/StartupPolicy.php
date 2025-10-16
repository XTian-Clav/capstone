<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\Startup;
use Illuminate\Auth\Access\HandlesAuthorization;

class StartupPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:Startup');
    }

    public function view(AuthUser $authUser, Startup $startup): bool
    {
        return $authUser->can('View:Startup');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:Startup');
    }

    public function update(AuthUser $authUser, Startup $startup): bool
    {
        return $authUser->can('Update:Startup');
    }

    public function delete(AuthUser $authUser, Startup $startup): bool
    {
        return $authUser->can('Delete:Startup');
    }

    public function restore(AuthUser $authUser, Startup $startup): bool
    {
        return $authUser->can('Restore:Startup');
    }

    public function forceDelete(AuthUser $authUser, Startup $startup): bool
    {
        return $authUser->can('ForceDelete:Startup');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:Startup');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:Startup');
    }

    public function replicate(AuthUser $authUser, Startup $startup): bool
    {
        return $authUser->can('Replicate:Startup');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:Startup');
    }

}