<?php

namespace App\Policies;

use App\Models\UserInvite;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserInvitePolicy
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
        return ($user->isAdmin() && isAdminOriginated()) || $user->hasRole('Manage Connected Customers Users');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\User  $user
     * @param  \App\Models\UserInvite  $userInvite
     * @return mixed
     */
    public function view(User $user, UserInvite $userInvite)
    {
        return ($user->isAdmin() && isAdminOriginated()) || $user->hasRole('Manage Connected Customers Users');
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return ($user->customer->customer_type == 'corporate') && $user->hasRole('Manage Connected Customers Users');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\User  $user
     * @param  \App\Models\UserInvite  $userInvite
     * @return mixed
     */
    public function update(User $user, UserInvite $userInvite)
    {
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\Models\UserInvite  $userInvite
     * @return mixed
     */
    public function delete(User $user, UserInvite $userInvite)
    {
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\User  $user
     * @param  \App\Models\UserInvite  $userInvite
     * @return mixed
     */
    public function restore(User $user, UserInvite $userInvite)
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\Models\UserInvite  $userInvite
     * @return mixed
     */
    public function forceDelete(User $user, UserInvite $userInvite)
    {
        return false;
    }
}
