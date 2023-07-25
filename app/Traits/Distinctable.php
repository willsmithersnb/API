<?php

namespace App\Traits;

trait Distinctable
{
    abstract function input();

    /*
    *   Extends a ModelFilter to allow distinguishing by specific columns
    *
    *   @param Array $columns The columns to be selecting within the current model.
    */
    public function distinctOn(array $columns)
    {

        if (sizeof($columns) > 10) {
            return $this;
        }

        $visible = $this->query->getModel()->getVisible();
        $selected_cols = array_intersect($columns, $visible);

        if (sizeof($selected_cols) > 0) {
            $this->distinct($selected_cols);
            $sort_col = $this->input('sort_col', '');
            foreach ($columns as $column) {
                if($column == $sort_col){
                    continue;
                }
                $this->orderBy($column);
            }
        }

        return $this;
    }
}
