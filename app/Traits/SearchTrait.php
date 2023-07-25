<?php

namespace App\Traits;

trait SearchTrait
{
    public function search($keyword)
    {
        return $this->where(function ($query) use ($keyword) {
            foreach ($this->search as $field) {
                if (str_contains($field, '.')) {
                    $related_search = explode('.', $field);
                    $query->with($related_search[0])->orWhereHas($related_search[0], function ($q) use ($keyword, $related_search) {
                        $q->where($related_search[1], 'ILIKE', '%' . $keyword . '%');
                    });
                } else {
                    $query->orWhere($field, 'ILIKE', '%' . $keyword . '%');
                }
            }
        });
    }
}
