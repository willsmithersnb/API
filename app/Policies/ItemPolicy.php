<?php

namespace App\Policies;

use App\Models\Item;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ItemPolicy
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
        return true;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Item $item
     * @return mixed
     */
    public function view(User $user, Item $item)
    {
        return $user->isAdmin() || $user->hasSameCustomer($item);
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
     * @param  \App\Models\Item $item
     * @return mixed
     */
    public function update(User $user, Item $item)
    {
        if (get_class($item->itemList->item_listable) == "App\Models\Order") {
            return false;
        }
        return true;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Item $item
     * @return mixed
     */
    public function delete(User $user, Item $item)
    {
        if (get_class($item->itemList->item_listable) == "App\Models\Order") {
            return false;
        }
        return true;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Item $item
     * @return mixed
     */
    public function restore(User $user, Item $item)
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Item $item
     * @return mixed
     */
    public function forceDelete(User $user, Item $item)
    {
        return false;
    }
}
