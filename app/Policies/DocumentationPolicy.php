<?php

namespace App\Policies;

use App\Models\Documentation;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class DocumentationPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Documentation  $documentation
     * @return mixed
     */
    public function view(?User $user, Documentation $documentation)
    {
        return true;
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->isAdmin() && isAdminOriginated();
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Documentation  $documentation
     * @return mixed
     */
    public function update(User $user, Documentation $documentation)
    {
        return $user->isAdmin() && isAdminOriginated();
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Documentation  $documentation
     * @return mixed
     */
    public function delete(User $user, Documentation $documentation)
    {
        return $user->isAdmin() && isAdminOriginated();
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Documentation  $documentation
     * @return mixed
     */
    public function restore(User $user, Documentation $documentation)
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Documentation  $documentation
     * @return mixed
     */
    public function forceDelete(User $user, Documentation $documentation)
    {
        return false;
    }
}
