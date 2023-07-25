<?php

namespace App\Policies;

use App\Models\PricingRule;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PricingRulePolicy
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
     * @param  \App\Models\PricingRule  $pricing_rule
     * @return mixed
     */
    public function view(?User $user, PricingRule $pricing_rule)
    {
        return true;
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->isAdmin() && isAdminOriginated();
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\User  $user
     * @param  \App\Models\PricingRule  $pricing_rule
     * @return mixed
     */
    public function update(User $user, PricingRule $pricing_rule)
    {
        return $user->isAdmin() && isAdminOriginated();
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\Models\PricingRule  $pricing_rule
     * @return mixed
     */
    public function delete(User $user, PricingRule $pricing_rule)
    {
        return $user->isAdmin() && isAdminOriginated();
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\User  $user
     * @param  \App\Models\PricingRule  $pricing_rule
     * @return mixed
     */
    public function restore(User $user, PricingRule $pricing_rule)
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\Models\PricingRule  $pricing_rule
     * @return mixed
     */
    public function forceDelete(User $user, PricingRule $pricing_rule)
    {
        return false;
    }
}
