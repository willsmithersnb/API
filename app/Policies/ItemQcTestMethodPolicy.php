<?php

namespace App\Policies;

use App\Models\ItemQcTestMethod;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ItemQcTestMethodPolicy
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
        return $user->isAdmin() && isAdminOriginated();
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\User  $user
     * @param  \App\Models\ItemQcTestMethod  $itemQcTestMethod
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, ItemQcTestMethod $itemQcTestMethod)
    {
        return $user->isAdmin() && isAdminOriginated();
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        return $user->isAdmin() && isAdminOriginated();
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\User  $user
     * @param  \App\Models\ItemQcTestMethod  $itemQcTestMethod
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, ItemQcTestMethod $itemQcTestMethod)
    {
        return $user->isAdmin() && isAdminOriginated();
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\Models\ItemQcTestMethod  $itemQcTestMethod
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, ItemQcTestMethod $itemQcTestMethod)
    {
        return $user->isAdmin() && isAdminOriginated();
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\User  $user
     * @param  \App\Models\ItemQcTestMethod  $itemQcTestMethod
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, ItemQcTestMethod $itemQcTestMethod)
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\Models\ItemQcTestMethod  $itemQcTestMethod
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, ItemQcTestMethod $itemQcTestMethod)
    {
        return false;
    }
}
