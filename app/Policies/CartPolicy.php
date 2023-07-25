<?php

namespace App\Policies;

use App\Models\Cart;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CartPolicy
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
     * @param  \App\Models\Cart  $cart
     * @return mixed
     */
    public function view(User $user, Cart $cart)
    {
        return ($user->isAdmin() && isAdminOriginated()) || $cart->customer_id == $user->customer_id;
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
     * @param  \App\Models\Cart  $cart
     * @return mixed
     */
    public function update(User $user, Cart $cart)
    {
        return ($user->isAdmin() && isAdminOriginated()) || $cart->customer_id == $user->customer_id;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Cart  $cart
     * @return mixed
     */
    public function delete(User $user, Cart $cart)
    {
        return ($user->isAdmin() && isAdminOriginated()) || $cart->customer_id == $user->customer_id;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Cart  $cart
     * @return mixed
     */
    public function restore(User $user, Cart $cart)
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Cart  $cart
     * @return mixed
     */
    public function forceDelete(User $user, Cart $cart)
    {
        return false;
    }
}
