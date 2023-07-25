<?php

namespace App\Providers;

use App\Scopes\CustomerFilteredScope;
use Illuminate\Auth\EloquentUserProvider;

class AuthUserProvider extends EloquentUserProvider
{

    protected function newModelQuery($model = null)
    {
        $modelQuery = parent::newModelQuery();
        return $modelQuery->withoutGlobalScope(CustomerFilteredScope::class);
    }
}
