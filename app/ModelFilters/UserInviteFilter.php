<?php

namespace App\ModelFilters;

use App\Traits\SearchTrait;
use App\Traits\Sortable;
use Carbon\Carbon;
use EloquentFilter\ModelFilter;
use Illuminate\Support\Str;

class UserInviteFilter extends ModelFilter
{
    use Sortable;
    use SearchTrait;

    protected $sortableColumns = [
        'id', 'email', 'first_name', 'last_name', 'expires_at', 'accepted_at'
    ];

    protected $search = [
        'email', 'first_name', 'last_name', 'invited_by', 'accepted_at'
    ];

    /**
     * Related Models that have ModelFilters as well as the method on the ModelFilter
     * As [relationMethod => [input_key1, input_key2]].
     *
     * @var array
     */
    public $relations = [];


    public function email($email)
    {
        return $this->where('email', 'ILIKE', '%' . $email . '%');
    }

    public function invitedBy($invited_by)
    {
        return $this->where('invited_by', $invited_by);
    }

    public function firstName($first_name)
    {
        return $this->where('first_name', 'ILIKE', '%' . $first_name . '%');
    }

    public function lastName($last_name)
    {
        return $this->where('last_name', 'ILIKE', '%' . $last_name . '%');
    }

    public function status($status)
    {
        if (Str::lower($status) == "pending") {
            return $this->where('expires_at', '>', Carbon::now()->format('Y-m-d H:i:s'))->where('accepted_at', null);
        } else if (Str::lower($status) == "expired") {
            return $this->where('expires_at', '<=', Carbon::now())->where('accepted_at', null);
        } else if (Str::lower($status) == "accepted") {
            return $this->where('accepted_at', '!=', null);
        }
    }
}
