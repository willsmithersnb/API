<?php

namespace App\Policies;

use App\Models\DeviceFirmware;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class DeviceFirmwarePolicy
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
        return false;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\User  $user
     * @param  \App\Models\DeviceFirmware  $deviceFirmware
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, DeviceFirmware $deviceFirmware)
    {
        return false;
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
     * @param  \App\Models\DeviceFirmware  $deviceFirmware
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, DeviceFirmware $deviceFirmware)
    {
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\Models\DeviceFirmware  $deviceFirmware
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, DeviceFirmware $deviceFirmware)
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\User  $user
     * @param  \App\Models\DeviceFirmware  $deviceFirmware
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, DeviceFirmware $deviceFirmware)
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\Models\DeviceFirmware  $deviceFirmware
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, DeviceFirmware $deviceFirmware)
    {
        return false;
    }
}
