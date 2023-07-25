<?php

namespace App\Scopes;

use App\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Facades\Auth;

class SeatOwnedByCustomerScope implements Scope
{
    public function apply(Builder $builder, Model $model)
    {
        if (Auth::hasUser() && Auth::check()) {
            $user = Auth::user();

            if (!$user->hasRole(['admin', 'super-admin'])) {
                $builder->whereIn('user_id', User::select('id')->where('customer_id', $user->customer_id));
            }
        }
    }
}
