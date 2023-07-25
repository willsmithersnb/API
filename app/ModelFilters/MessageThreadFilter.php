<?php

namespace App\ModelFilters;

use App\Models\Message;
use App\Models\MessageThread;
use App\Traits\Sortable;
use EloquentFilter\ModelFilter;

class MessageThreadFilter extends ModelFilter
{
    use Sortable;

    protected $sortableColumns = [
        'customer_id', 'subject', 'messages.created_at', 'messages.body'
    ];

    const FILTERABLE_COLUMNS = [
        'subject' => 'sometimes|string',
        'customer_id' => 'sometimes|integer',
        'sort_col' => 'sometimes|in:subject,customer_id',
        'sort_dir' => 'sometimes|in:asc,ASC,desc,DESC'
    ];

    /**
     * Related Models that have ModelFilters as well as the method on the ModelFilter
     * As [relationMethod => [input_key1, input_key2]].
     *
     * @var array
     */
    public $relations = [];

    public function customerId($customer_id)
    {
        return $this->where('customer_id', 'ILIKE', '%' . $customer_id . '%');
    }

    public function subject($subject)
    {
        return $this->where('subject', 'ILIKE', '%' . $subject . '%');
    }

    public function search($search_by)
    {
        $search_term = '%' . $search_by . '%';
        return $this->where(function ($query) use ($search_term) {
            $query->orWhere('subject', 'ILIKE',  $search_term)
                ->orWhereIn('id', Message::select('message_thread_id')->distinct('message_thread_id')->where('body', 'ILIKE', $search_term));
        });
    }
}
