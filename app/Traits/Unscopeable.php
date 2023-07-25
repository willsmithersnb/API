<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\{Builder};

trait Unscopeable
{
    abstract function apply();

    /**
     * Extend the query builder with a function to remove global scope.
     *
     * @param Builder $builder
     */
    public function extend(Builder $builder)
    {
        $builder->macro('unScoped', function (Builder $builder) {
            return $builder->withoutGlobalScope($this);
        });
    }
}
