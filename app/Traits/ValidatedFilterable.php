<?php

namespace App\Traits;

use Dingo\Api\Exception\ResourceException;
use Illuminate\Support\Facades\Validator;
use EloquentFilter\Filterable;

trait ValidatedFilterable
{
    use Filterable {
        Filterable::scopeFilter as parentScopeFilter;
    }

    public function scopeFilter($query, array $input = [], $filter = null)
    {
        if ($filter === null) {
            $filter = $this->getModelFilterClass();
        }

        $filterRules = $filter::FILTERABLE_COLUMNS;

        $validator = Validator::make($input, $filterRules);

        if ($validator->fails()) {
            throw new ResourceException('Missing Required Fields', $validator->errors());
        }

        return $this->parentScopeFilter($query, $input, null);
    }
}
