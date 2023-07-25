<?php

namespace App\Transformer;

use Illuminate\Database\Eloquent\Model;

class UserInviteTransformer extends ModelTransformer
{
    protected $availableIncludes = ['invited_by', 'customer'];

    public function includeInvitedBy(Model $model)
    {
        return $this->item($model->invitedBy, new UserTransformer(), false);
    }

    public function includeCustomer(Model $model)
    {
        return $this->item($model->customer, new CustomerTransformer(), false);
    }
}
