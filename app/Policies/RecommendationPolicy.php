<?php

namespace App\Policies;

use App\Models\NBAI\Recommendation;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Auth;

class RecommendationPolicy
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
     * @param  \App\Models\NBAI\Recommendation  $recommendation
     * @return mixed
     */
    public function view(User $user, Recommendation $recommendation)
    {
        if (Auth::hasUser() && Auth::check()) {
            $user = Auth::user();

            if (($user->seat != null && $user->seat->status == 'active') || $user->hasRole(['admin', 'super-admin'])) {
                return true;
            }
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
        return $user->hasRole(['admin', 'super-admin']);
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\User  $user
     * @param  \App\Models\NBAI\Recommendation  $recommendation
     * @return mixed
     */
    public function update(User $user, Recommendation $recommendation)
    {
        return $user->hasRole(['admin', 'super-admin']);
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\Models\NBAI\Recommendation  $recommendation
     * @return mixed
     */
    public function delete(User $user, Recommendation $recommendation)
    {
        return $user->hasRole(['admin', 'super-admin']);
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\User  $user
     * @param  \App\Models\NBAI\Recommendation  $recommendation
     * @return mixed
     */
    public function restore(User $user, Recommendation $recommendation)
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\Models\NBAI\Recommendation  $recommendation
     * @return mixed
     */
    public function forceDelete(User $user, Recommendation $recommendation)
    {
        return false;
    }
}
