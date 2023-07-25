<?php

namespace App\Policies;

use App\Models\Firmware;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class FirmwarePolicy
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
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Firmware  $firmware
     * @return mixed
     */
    public function view(User $user, Firmware $firmware)
    {
        if (($firmware->uploaded_by != null && $firmware->uploaded_by  == $user->id) || $user->isAdmin()) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Firmware  $firmware
     * @return mixed
     */
    public function update(User $user, Firmware $firmware)
    {
        if (($firmware->uploaded_by != null && $firmware->uploaded_by  == $user->id) || $user->isAdmin()) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Firmware  $firmware
     * @return mixed
     */
    public function delete(User $user, Firmware $firmware)
    {
        if (($firmware->uploaded_by != null && $firmware->uploaded_by  == $user->id) || $user->isAdmin()) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Firmware  $firmware
     * @return mixed
     */
    public function restore(User $user, Firmware $firmware)
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Firmware  $firmware
     * @return mixed
     */
    public function forceDelete(User $user, Firmware $firmware)
    {
        return false;
    }
}
