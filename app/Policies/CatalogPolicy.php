<?php

namespace App\Policies;

use App\Models\Catalog;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CatalogPolicy
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
     * @param  \App\Models\Catalog  $catalog
     * @return mixed
     */
    public function view(?User $user, Catalog $catalog)
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
     * @param  \App\Models\Catalog  $catalog
     * @return mixed
     */
    public function update(User $user, Catalog $catalog)
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Catalog  $catalog
     * @return mixed
     */
    public function delete(User $user, Catalog $catalog)
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Catalog  $catalog
     * @return mixed
     */
    public function restore(User $user, Catalog $catalog)
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Catalog  $catalog
     * @return mixed
     */
    public function forceDelete(User $user, Catalog $catalog)
    {
        return false;
    }
}
