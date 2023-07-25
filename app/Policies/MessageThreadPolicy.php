<?php

namespace App\Policies;

use App\Models\MessageThread;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class MessageThreadPolicy
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
     * @param  \App\Models\MessageThread  $message_thread
     * @return mixed
     */
    public function view(User $user, MessageThread $message_thread)
    {
        if (($user->customer_id != null && $user->customer_id == $message_thread->customer_id) || $user->isAdmin()) {
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
        return true;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\User  $user
     * @param  \App\Models\MessageThread  $message_thread
     * @return mixed
     */
    public function update(User $user, MessageThread $message_thread)
    {
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\Models\MessageThread  $message_thread
     * @return mixed
     */
    public function delete(User $user, MessageThread $message_thread)
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\User  $user
     * @param  \App\Models\MessageThread  $message_thread
     * @return mixed
     */
    public function restore(User $user, MessageThread $message_thread)
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\Models\MessageThread  $message_thread
     * @return mixed
     */
    public function forceDelete(User $user, MessageThread $message_thread)
    {
        return false;
    }
}
