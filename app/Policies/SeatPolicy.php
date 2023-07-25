<?php

namespace App\Policies;

use App\Models\NBAI\Seat;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Auth;

class SeatPolicy
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
        return Auth::check();
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\User  $user
     * @param  \App\Models\NBAI\Seat  $seat
     * @return mixed
     */
    public function view(User $user, Seat $seat)
    {
        if (
            $user->hasRole(['admin', 'super-admin']) ||
            User::where('customer_id', $user->customer_id)->where('id', $seat->user_id)->exists()
        ) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return Auth::check();
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\User  $user
     * @param  \App\Models\NBAI\Seat  $seat
     * @return mixed
     */
    public function update(User $user, Seat $seat)
    {
        if (
            $user->hasRole(['admin', 'super-admin']) ||
            User::where('customer_id', $user->customer_id)->where('id', $seat->user_id)->exists()
        ) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\Models\NBAI\Seat  $seat
     * @return mixed
     */
    public function delete(User $user, Seat $seat)
    {
        return $user->hasRole(['admin', 'super-admin']);
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\User  $user
     * @param  \App\Models\NBAI\Seat  $seat
     * @return mixed
     */
    public function restore(User $user, Seat $seat)
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\Models\NBAI\Seat  $seat
     * @return mixed
     */
    public function forceDelete(User $user, Seat $seat)
    {
        return false;
    }
}
