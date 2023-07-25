<?php

namespace App\Policies;

use App\Models\ProductOption;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProductOptionPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(?User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\User  $user
     * @param  \App\Models\ProductOption  $productOption
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(?User $user, ProductOption $productOption)
    {
        return true;
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
     * @param  \App\Models\ProductOption  $productOption
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, ProductOption $productOption)
    {
        return ($user->isAdmin() && isAdminOriginated());
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\Models\ProductOption  $productOption
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, ProductOption $productOption)
    {
        return ($user->isAdmin() && isAdminOriginated());
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\User  $user
     * @param  \App\Models\ProductOption  $productOption
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, ProductOption $productOption)
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\Models\ProductOption  $productOption
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, ProductOption $productOption)
    {
        return false;
    }
}
