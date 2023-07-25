<?php

namespace App\ModelFilters;

use App\Traits\Sortable;
use EloquentFilter\ModelFilter;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class RecommendationFilter extends ModelFilter
{
    use Sortable;

    protected $sortableColumns = [
        'id', 'ingredient_id', 'deviation', 'concentration_high', 'concentration_mid', 'concentration_low', 'concentration_unit', 'score', 'quote', 'criticalQualityAttributes.name', 'ingredient.ingredient_type', 'ingredient.name', 'researchPaper.title'
    ];

    const FILTERABLE_COLUMNS = [
        'cellType_name' => 'sometimes|string',
        'ingredient_id' => 'sometimes|integer',
        'deviation' => 'sometimes|in:increase,decrease,maintain',
        'concentration_high' => 'sometimes|integer|min:0',
        'concentration_mid' => 'sometimes|integer|min:0',
        'concentration_low' => 'sometimes|integer|min:0',
        'concentration_unit' => 'sometimes|integer',
        'score' => 'sometimes|numeric|min:0.001|max:100',
        'quote' => 'sometimes|string',
        'sort_col' => 'sometimes|in:cellType_name,ingredient_id,deviation,concentration_high,concentration_mid,concentration_low,concentration_unit,score,quote,count',
        'sort_dir' => 'sometimes|in:asc,ASC,desc,DESC'
    ];

    /**
     * Related Models that have ModelFilters as well as the method on the ModelFilter
     * As [relationMethod => [input_key1, input_key2]].
     *
     * @var array
     */
    public $relations = [
        'cellType' => [
            'cellType_name' => 'name',
        ],
        'criticalQualityAttributes' => [
            'cqa_name' => 'name',
        ],
        'cellMediaRecommendations' => [
            'cellMedia_name' => 'name',
            'cellMedia_ids' => 'cellMedia_ids'
        ]
    ];

    protected function setup()
    {
        if (!Auth::check()) {
            $this->blacklist = [
                'ingredient',
                'ingredients',
                'cqa',
                'ingredientName',
                'ingredientIngredientType',
                'deviation',
                'concentrationHigh',
                'concentrationMid',
                'concentrationLow',
                'concentrationUnit',
                'score',
                'quote',
                'filterRank'
            ];

            $this->sortableColumns = ['score'];
        }
    }

    public function ingredient($id)
    {
        return $this->where('ingredient_id', $id);
    }
    public function ingredients($ids)
    {
        return $this->whereIn('ingredient_id', json_decode($ids));
    }

    public function cqa($id)
    {
        return $this->where('cqa_id', $id);
    }

    public function ingredientName($id)
    {
        return $this->related('ingredient', 'name', 'like',  '%' . $id . '%');
    }

    public function ingredientIngredientType($ingredient_type)
    {
        return $this->related('ingredient', 'ingredient_type', 'like',  '%' . $ingredient_type . '%');
    }

    public function deviation($deviation)
    {
        return $this->where('deviation', $deviation);
    }

    public function deviations($deviations)
    {
        return $this->whereIn('deviation', $deviations);
    }

    public function concentrationHigh($concentrationHigh)
    {
        return $this->where('concentration_high', $concentrationHigh);
    }

    public function concentrationMid($concentrationMid)
    {
        return $this->where('concentration_mid', $concentrationMid);
    }

    public function concentrationLow($concentrationLow)
    {
        return $this->where('concentration_low', $concentrationLow);
    }

    public function concentrationUnit($concentrationUnit)
    {
        return $this->where('concentration_unit', $concentrationUnit);
    }

    public function score($score)
    {
        return $this->where('score', $score);
    }

    public function quote($quote)
    {
        return $this->whereLike('quote', '%' . $quote . '%');
    }

    public function filterRank($input)
    {
        $input_decoded = json_decode($input);

        $this->where(function ($query) use ($input_decoded) {
            foreach ($input_decoded as $value_list) {
                $cqaId = $value_list->cqa_id;
                $deviation = Str::lower($value_list->deviation);
                $rank = $value_list->rank;
                $query->orWhere(function ($query) use ($cqaId, $deviation) {
                    $query->where('cqa_id', '=', $cqaId)->where('deviation', '=', $deviation);
                });
            }
        });
        return $this;
    }
}
