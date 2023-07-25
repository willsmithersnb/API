<?php

namespace App\Policies;

use App\Models\Formula;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class FormulaPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function viewAny(?User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Formula  $formula
     * @return mixed
     */
    public function view(?User $user, Formula $formula)
    {
        return $formula->customer_id == null || (isAdminOriginated() && optional($user)->isAdmin()) || $formula->customer_id == optional($user)->customer_id;
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
     * @param  \App\Models\Formula  $formula
     * @return mixed
     */
    public function update(User $user, Formula $formula)
    {
        return ($user->isAdmin() && isAdminOriginated()) || $formula->customer_id == $user->customer_id;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Formula  $formula
     * @return mixed
     */
    public function delete(User $user, Formula $formula)
    {
        return ($user->isAdmin() && isAdminOriginated());
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Formula  $formula
     * @return mixed
     */
    public function restore(User $user, Formula $formula)
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Formula  $formula
     * @return mixed
     */
    public function forceDelete(User $user, Formula $formula)
    {
        return false;
    }
}
