<?php

namespace App\Policies;

use App\Models\ProductPackagingOption;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProductPackagingOptionPolicy
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
     * @param  \App\Models\ProductPackagingOption  $productPackagingOption
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(?User $user, ProductPackagingOption $productPackagingOption)
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
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\User  $user
     * @param  \App\Models\ProductPackagingOption  $productPackagingOption
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, ProductPackagingOption $productPackagingOption)
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\Models\ProductPackagingOption  $productPackagingOption
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, ProductPackagingOption $productPackagingOption)
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\User  $user
     * @param  \App\Models\ProductPackagingOption  $productPackagingOption
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, ProductPackagingOption $productPackagingOption)
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\Models\ProductPackagingOption  $productPackagingOption
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, ProductPackagingOption $productPackagingOption)
    {
        return false;
    }
}
