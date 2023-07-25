<?php
namespace App\Scopes;

use App\Models\MessageThread;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Facades\Auth;

class MessageOwnedByCustomerScope implements Scope
{
    public function apply(Builder $builder, Model $model)
    {
        if (Auth::hasUser() && Auth::check()) {

            $user = Auth::user();

            if (!($user->isAdmin() && isAdminOriginated())) {
                $builder->whereIn('message_thread_id', MessageThread::select('id')->where('customer_id', $user->customer_id));
            }

        }
    }
}
