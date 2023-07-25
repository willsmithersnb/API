<?php

namespace App\Policies;

use App\Models\Quote;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class QuotePolicy
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
     * @param  \App\Models\Quote  $quote
     * @return mixed
     */
    public function view(User $user, Quote $quote)
    {
        return ($user->isAdmin() && isAdminOriginated()) || $quote->customer_id == $user->customer_id;
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
     * @param  \App\Models\Quote  $quote
     * @return mixed
     */
    public function update(User $user, Quote $quote)
    {
        return ($user->isAdmin() && isAdminOriginated()) || $quote->customer_id == $user->customer_id;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Quote  $quote
     * @return mixed
     */
    public function delete(User $user, Quote $quote)
    {
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Quote  $quote
     * @return mixed
     */
    public function restore(User $user, Quote $quote)
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Quote  $quote
     * @return mixed
     */
    public function forceDelete(User $user, Quote $quote)
    {
        return false;
    }
}
