<?php

namespace App\Traits;

trait SelectTrait
{
    abstract function input();

    /*
    *   Extends a ModelFilter to allow selecting specific columns
    *
    *   @param Array $columns The columns to be selecting within the current model.
    */
    public function selectedCol(array $columns)
    {
        if (sizeof($columns) > 10) {
            return $this;
        }

        $visible = $this->query->getModel()->getVisible();
        $selected_cols = array_intersect($columns, $visible);

        if (sizeof($selected_cols) > 0) {
            $this->select($selected_cols);
        }

        return $this;
    }
}
