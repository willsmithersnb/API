<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

trait Sortable
{
    abstract function input();

    /*
    *   Extends a ModelFilter to allow sorting
    *
    *   @param string $column The column to be sorted within the current model.
    *   @param string $direction Sort direction; ascending or descending. Default is set to descending.
    */
    public function sortCol(string $column)
    {
        if (in_array($column, $this->sortableColumns ?? [])) {
            $order_by = $column;
            if (strpos($column, '.') !== false) {
                $relation_ref = explode('.', $column);
                $order_by_model = $this->getRelatedModel($relation_ref[0]);
                $relationshipTypeArray = get_class($this->query->getModel()->{$relation_ref[0]}());
                switch ($relationshipTypeArray) {
                    case BelongsTo::class:
                        $order_by = $order_by_model::select($relation_ref[1])->whereColumn($this->query->getModel()->{$relation_ref[0]}()->getForeignKeyName(), $order_by_model->getTable() . '.' . $this->query->getModel()->{$relation_ref[0]}()->getOwnerKeyName());
                        break;
                    case HasMany::class:
                        $order_by = $order_by_model::select($relation_ref[1])->whereColumn($this->query->getModel()->{$relation_ref[0]}()->getForeignKeyName(), $this->query->getModel()->{$relation_ref[0]}()->getQualifiedParentKeyName())->latest()->take(1);
                        break;
                    case HasOne::class:
                        $order_by = $order_by_model::select($relation_ref[1])->whereColumn($this->query->getModel()->{$relation_ref[0]}()->getForeignKeyName(), $this->query->getModel()->{$relation_ref[0]}()->getQualifiedParentKeyName());
                        break;
                    default:
                        break;
                }
            }
            $this->orderBy($order_by, $this->input('sort_dir', 'DESC'));
            return $this;
        } else {
            return $this;
        }
    }
}
