<?php

namespace App\Models\NBAI;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ResearchPaper extends Model
{
    protected $table = 'research_papers';
    public $timestamps = true;

    use SoftDeletes;

    protected $dates = ['deleted_at'];
    protected $fillable = array('title', 'journal_id', 'link', 'year', 'author_name', 'cited_by', 'years_out');
    protected $visible = array('title', 'journal_id', 'link', 'year', 'author_name', 'cited_by', 'created_at', 'updated_at');

    public function journal()
    {
        return $this->belongsTo('App\Models\NBAI\Journal');
    }

    public function recommendations()
    {
        return $this->hasMany('App\Models\NBAI\Recommendation');
    }
}
