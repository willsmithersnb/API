<?php

namespace App\Policies;

use App\Models\ProductQcTest;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProductQcTestPolicy
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
     * @param  \App\Models\ProductQcTest  $productQcTest
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(?User $user, ProductQcTest $productQcTest)
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
     * @param  \App\Models\ProductQcTest  $productQcTest
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, ProductQcTest $productQcTest)
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\Models\ProductQcTest  $productQcTest
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, ProductQcTest $productQcTest)
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\User  $user
     * @param  \App\Models\ProductQcTest  $productQcTest
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, ProductQcTest $productQcTest)
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\Models\ProductQcTest  $productQcTest
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, ProductQcTest $productQcTest)
    {
        return false;
    }
}
