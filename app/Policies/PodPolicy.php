<?php

namespace App\Policies;

use App\Models\Pod;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PodPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(User $user)
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Pod  $pod
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, Pod $pod)
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        return ($user->isAdmin() && isAdminOriginated());
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Pod  $pod
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Pod $pod)
    {
        return ($user->isAdmin() && isAdminOriginated());
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Pod  $pod
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Pod $pod)
    {
        return ($user->isAdmin() && isAdminOriginated());
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Pod  $pod
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, Pod $pod)
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Pod  $pod
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, Pod $pod)
    {
        return false;
    }
}
