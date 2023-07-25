<?php

namespace App\Scopes;

use App\Traits\Unscopeable;
use Illuminate\Database\Eloquent\{Builder, Model, Scope};
use Illuminate\Support\Facades\Auth;

class NullableCustomerFilteredScope implements Scope
{
    use Unscopeable;

    public function apply(Builder $builder, Model $model)
    {
        if (Auth::check() && Auth::hasUser()) {
            $user = Auth::user();
            if ($user->isAdmin() && isAdminOriginated()) {
                return $builder;
            }
            return $builder->where('customer_id', $user->customer_id)->orWhere('customer_id', null);
        } else {
            return $builder->where('customer_id', null);
        }
    }
}
