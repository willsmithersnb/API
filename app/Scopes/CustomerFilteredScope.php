<?php

namespace App\Scopes;

use App\Traits\Unscopeable;
use Illuminate\Database\Eloquent\{Builder, Model, Scope};
use Illuminate\Support\Facades\Auth;

class CustomerFilteredScope implements Scope
{
    use Unscopeable;

    protected $key_field;

    public function __construct($key_field = 'customer_id')
    {
        $this->key_field = $key_field;
    }

    public function apply(Builder $builder, Model $model)
    {
        if (Auth::check() && Auth::hasUser()) {
            $user = Auth::user();

            if ($user->isAdmin() && isAdminOriginated()) {
                return $builder;
            }
            return $builder->where($this->key_field, $user->customer_id);
        }
        return $builder->limit(0);
    }
}
