<?php

namespace App\Scopes;

use App\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Facades\Auth;

class LimitRecommendationsScope implements Scope
{
    public function apply(Builder $builder, Model $model)
    {
        if (Auth::hasUser() && Auth::check()) {
            $user = Auth::user();

            if (($user->seat != null && $user->seat->status == 'active') || $user->hasRole(['admin', 'super-admin'])) {
                return $builder;
            }
        }

        return $builder->limit(1);
    }
}
