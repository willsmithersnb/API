<?php

namespace App\ModelFilters;

use App\Traits\Sortable;
use EloquentFilter\ModelFilter;

class MessageFilter extends ModelFilter
{
    use Sortable;

    protected $sortableColumns = [
        'body', 'user_id', 'message_thread_id', 'created_at'
    ];

    const FILTERABLE_COLUMNS = [
        'body' => 'sometimes|string',
        'user_id' => 'sometimes|integer',
        'message_thread_id' => 'sometimes|integer',
        'sort_col' => 'sometimes|in:body,user_id,message_thread_id',
        'sort_dir' => 'sometimes|in:asc,ASC,desc,DESC'
    ];

    /**
     * Related Models that have ModelFilters as well as the method on the ModelFilter
     * As [relationMethod => [input_key1, input_key2]].
     *
     * @var array
     */
    public $relations = [];


    public function body($body)
    {
        return $this->whereLike('body', $body);
    }

    public function userId($user_id)
    {
        return $this->whereLike('user_id', $user_id);
    }

    public function messageThreadId($message_thread_id)
    {
        return $this->whereLike('message_thread_id', $message_thread_id);
    }
}
