<?php

namespace App\ModelFilters;

use App\User;
use App\Traits\Sortable;
use App\Traits\Distinctable;
use App\Traits\SearchTrait;
use EloquentFilter\Filterable;
use EloquentFilter\ModelFilter;

class SeatFilter extends ModelFilter
{
    use Sortable, Distinctable, SearchTrait;

    protected $sortableColumns = [
        'expires_at', 'status', 'payment_type', 'user_id', 'created_at'
    ];

    protected $search = [
        'user.email'
    ];

    const FILTERABLE_COLUMNS = [
        'user_id' => 'sometimes|string',
        'status' => 'sometimes|string',
        'payment_type' => 'sometimes|string',
        'expires_at' => 'sometimes|date',
        'sort_col' => 'sometimes|in:user_id,expires_at,status,payment_type',
        'sort_dir' => 'sometimes|in:asc,ASC,desc,DESC'
    ];

    /**
     * Related Models that have ModelFilters as well as the method on the ModelFilter
     * As [relationMethod => [input_key1, input_key2]].
     *
     * @var array
     */
    public $relations = [];

    public function status($status)
    {
        return $this->where('status', $status);
    }

    public function paymentType($payment_type)
    {
        return $this->where('payment_type', $payment_type);
    }

    public function email($email)
    {
        return $this->whereIn('user_id', User::select('id')->where('email', $email));
    }

    public function user($id)
    {
        return $this->where('user_id', $id);
    }

    public function expiresAt($expires_at)
    {
        return $this->where('expires_at', $expires_at);
    }
}
