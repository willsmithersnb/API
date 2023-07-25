<?php

namespace App\Models\NBAI;

use App\Scopes\LimitRecommendationsScope;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Recommendation extends Model
{
    use Filterable;

    protected $table = 'recommendations';
    public $timestamps = true;

    use SoftDeletes;

    protected $dates = ['deleted_at'];
    protected $fillable = array('cell_type_id', 'cqa_id', 'ingredient_id', 'deviation', 'concentration_high', 'concentration_mid', 'concentration_low', 'concentration_unit', 'score', 'quote', 'research_paper_id');
    protected $visible = array('id', 'cell_type_id', 'cqa_id', 'ingredient_id', 'deviation', 'concentration_high', 'concentration_mid', 'concentration_low', 'concentration_unit', 'score', 'quote', 'research_paper_id', 'created_at', 'updated_at');

    public function cellMedia()
    {
        return $this->belongsToMany('App\Models\NBAI\CellMedia', 'cell_media_recommendations');
    }


    public function cellMediaRecommendations()
    {
        return $this->hasMany('App\Models\NBAI\CellMediaRecommendations', 'recommendation_id');
    }


    public function criticalQualityAttributes()
    {
        return $this->belongsTo('App\Models\NBAI\CriticalQualityAttribute', 'cqa_id');
    }

    public function ingredient()
    {
        return $this->belongsTo('App\Models\Ingredient');
    }

    public function cellType()
    {
        return $this->belongsTo('App\Models\CellType');
    }

    public function researchPaper()
    {
        return $this->belongsTo('App\Models\NBAI\ResearchPaper');
    }

    public function geneProteinExpressions()
    {
        return $this->belongsToMany('App\Models\NBAI\GeneProteinExpression', 'gpe_recommendations');
    }

    protected static function booted()
    {
        static::addGlobalScope(new LimitRecommendationsScope);
    }
}
