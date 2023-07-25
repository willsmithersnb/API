<?php

namespace App\Policies;

use App\Models\QcTest;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class QcTestPolicy
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
     * @param  \App\Models\QcTest  $qcTest
     * @return mixed
     */
    public function view(?User $user, QcTest $qcTest)
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
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\User  $user
     * @param  \App\Models\QcTest  $qcTest
     * @return mixed
     */
    public function update(User $user, QcTest $qcTest)
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\Models\QcTest  $qcTest
     * @return mixed
     */
    public function delete(User $user, QcTest $qcTest)
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\User  $user
     * @param  \App\Models\QcTest  $qcTest
     * @return mixed
     */
    public function restore(User $user, QcTest $qcTest)
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\Models\QcTest  $qcTest
     * @return mixed
     */
    public function forceDelete(User $user, QcTest $qcTest)
    {
        return false;
    }
}
