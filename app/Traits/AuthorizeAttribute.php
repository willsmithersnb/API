<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Model;

trait AuthorizeAttribute
{
    // Prevent Use in non transformer classes from using trait
    abstract public function transform(Model $model);

    protected $hiddenFields = [];

    protected function getHiddenFields()
    {
        return $this->hiddenFields;
    }
}
