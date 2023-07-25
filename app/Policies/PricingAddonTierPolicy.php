<?php

namespace App\Policies;

use App\Models\PricingAddonTier;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PricingAddonTierPolicy
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
     * @param  \App\Models\PricingAddonTier  $pricing_addon_tier
     * @return mixed
     */
    public function view(?User $user, PricingAddonTier $pricing_addon_tier)
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
     * @param  \App\Models\PricingAddonTier  $pricing_addon_tier
     * @return mixed
     */
    public function update(User $user, PricingAddonTier $pricing_addon_tier)
    {
        return $user->isAdmin() && isAdminOriginated();
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\Models\PricingAddonTier  $pricing_addon_tier
     * @return mixed
     */
    public function delete(User $user, PricingAddonTier $pricing_addon_tier)
    {
        return $user->isAdmin() && isAdminOriginated();
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\User  $user
     * @param  \App\Models\PricingAddonTier  $pricing_addon_tier
     * @return mixed
     */
    public function restore(User $user, PricingAddonTier $pricing_addon_tier)
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\Models\PricingAddonTier  $pricing_addon_tier
     * @return mixed
     */
    public function forceDelete(User $user, PricingAddonTier $pricing_addon_tier)
    {
        return false;
    }
}
