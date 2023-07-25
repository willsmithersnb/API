<?php

namespace App\ModelFilters;

use App\Traits\Sortable;
use App\User;
use EloquentFilter\ModelFilter;
use App\Traits\SearchTrait;

class UserFilter extends ModelFilter
{
    use Sortable;
    use SearchTrait;

    protected $sortableColumns = [
        'id', 'email', 'first_name', 'last_name', 'department', 'field_of_work', 'job_title', 'cell_type_interests', 'customer_id', 'company_name'
    ];

    protected $search = [
        'email', 'first_name', 'last_name', 'department', 'job_title', 'field_of_work', 'company_name'
    ];

    public $relations = [];

    public function email($email)
    {
        return $this->whereLike('email', $email);
    }

    public function firstName($first_name)
    {
        return $this->where('first_name', 'ILIKE', '%' . $first_name . '%');
    }

    public function lastName($last_name)
    {
        return $this->where('last_name', 'ILIKE', '%' . $last_name . '%');
    }

    public function department($department)
    {
        return $this->where('department', 'ILIKE', '%' . $department . '%');
    }

    public function fieldOfWork($field_of_work)
    {
        return $this->where('field_of_work', 'ILIKE', '%' . $field_of_work . '%');
    }

    public function jobTitle($job_title)
    {
        return $this->where('job_title', 'ILIKE', '%' . $job_title . '%');
    }

    public function cellTypeInterests($cell_type_interests)
    {
        return $this->where('cell_type_interests', 'ILIKE', '%' . $cell_type_interests . '%');
    }

    public function companyName($company_name)
    {
        return $this->where('company_name', $company_name);
    }

    public function customerId($customer_id)
    {
        return $this->where('customer_id', $customer_id);
    }

    public function roleName($role_names)
    {
        return $this->whereHas('roles', function ($q) use ($role_names) {
            return $q->whereIn('name', $role_names);
        });
    }
}
