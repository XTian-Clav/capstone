<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\Mentor;
use Illuminate\Auth\Access\HandlesAuthorization;

class MentorPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:Mentor');
    }

    public function view(AuthUser $authUser, Mentor $mentor): bool
    {
        return $authUser->can('View:Mentor');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:Mentor');
    }

    public function update(AuthUser $authUser, Mentor $mentor): bool
    {
        return $authUser->can('Update:Mentor');
    }

    public function delete(AuthUser $authUser, Mentor $mentor): bool
    {
        return $authUser->can('Delete:Mentor');
    }

    public function restore(AuthUser $authUser, Mentor $mentor): bool
    {
        return $authUser->can('Restore:Mentor');
    }

    public function forceDelete(AuthUser $authUser, Mentor $mentor): bool
    {
        return $authUser->can('ForceDelete:Mentor');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:Mentor');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:Mentor');
    }

    public function replicate(AuthUser $authUser, Mentor $mentor): bool
    {
        return $authUser->can('Replicate:Mentor');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:Mentor');
    }

}