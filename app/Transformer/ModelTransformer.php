<?php

namespace App\Transformer;

use App\Traits\AuthorizeAttribute;
use Illuminate\Database\Eloquent\Model;
use League\Fractal\TransformerAbstract;


class ModelTransformer extends TransformerAbstract
{
    use AuthorizeAttribute;

    /**
     * Turn this item object into a generic array
     *
     * @return array
     */
    public function transform(Model $model)
    {
        $model->makeHidden($this->getHiddenFields());
        return $model->toArray();
    }
}
