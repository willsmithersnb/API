<?php

namespace App\Policies;

use App\Models\Favorite;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class FavoritePolicy
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
     * @param  \App\Models\Favorite  $favorite
     * @return mixed
     */
    public function view(User $user, Favorite $favorite)
    {
        return ($user->isAdmin() && isAdminOriginated()) || $favorite->customer_id == $user->customer_id;
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Favorite  $favorite
     * @return mixed
     */
    public function update(User $user, Favorite $favorite)
    {
        return ($user->isAdmin() && isAdminOriginated()) || $favorite->customer_id == $user->customer_id;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Favorite  $favorite
     * @return mixed
     */
    public function delete(User $user, Favorite $favorite)
    {
        return ($user->isAdmin() && isAdminOriginated()) || $favorite->customer_id == $user->customer_id;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Favorite  $favorite
     * @return mixed
     */
    public function restore(User $user, Favorite $favorite)
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Favorite  $favorite
     * @return mixed
     */
    public function forceDelete(User $user, Favorite $favorite)
    {
        return false;
    }
}
