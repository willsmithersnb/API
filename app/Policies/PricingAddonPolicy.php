<?php

namespace App\Policies;

use App\Models\PricingAddon;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PricingAddonPolicy
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
     * @param  \App\Models\PricingAddon  $pricing_addon
     * @return mixed
     */
    public function view(?User $user, PricingAddon $pricing_addon)
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
     * @param  \App\Models\PricingAddon  $pricing_addon
     * @return mixed
     */
    public function update(User $user, PricingAddon $pricing_addon)
    {
        return $user->isAdmin() && isAdminOriginated();
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\Models\PricingAddon  $pricing_addon
     * @return mixed
     */
    public function delete(User $user, PricingAddon $pricing_addon)
    {
        return $user->isAdmin() && isAdminOriginated();
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\User  $user
     * @param  \App\Models\PricingAddon  $pricing_addon
     * @return mixed
     */
    public function restore(User $user, PricingAddon $pricing_addon)
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\Models\PricingAddon  $pricing_addon
     * @return mixed
     */
    public function forceDelete(User $user, PricingAddon $pricing_addon)
    {
        return false;
    }
}
