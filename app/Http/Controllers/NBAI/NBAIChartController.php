<?php

namespace App\Http\Controllers\NBAI;

use App\Http\Controllers\Controller;
use App\Models\NBAI\CellMediaRecommendations;
use App\Models\NBAI\GpeRecommendation;
use App\Models\NBAI\Recommendation;
use App\Transformer\CellMediaRecommendationsTransformer;
use App\Transformer\GpeRecommendationTransformer;
use App\Transformer\RecommendationsTransformer;
use Dingo\Api\Exception\ResourceException;
use Dingo\Api\Routing\Helpers;
use Illuminate\Http\Request;

class NBAIChartController extends Controller
{
    use Helpers;

    /**
     * Display aggregate Score per deviation for given set of filters.
     *
     * @return \Illuminate\Http\Response
     */
    public function aggregateScoreBy(Request $request, $column)
    {
        if (!in_array($column, ['cqa_id', 'ingredient_id'])) {
            throw new ResourceException('Invalid Aggregate Target');
        }
        $selectables = [];
        if ($request->has('no-deviation')) {
            $selectables = [$column];
        } else {
            $selectables = [$column, 'deviation'];
        }
        $recommendation_aggregate = Recommendation::withoutGlobalScopes()->select($selectables)
            ->selectRaw('SUM(score) as score, COUNT(score) as count')
            ->groupBy($selectables)
            ->filter($request->all())
            ->get()
            ->makeVisible(['count']);
        return $this->response->collection($recommendation_aggregate, new RecommendationsTransformer);
    }


    public function getMostCitedPaper(Request $request)
    {
        // TODO:Add logging for usage 
        $most_cited = CellMediaRecommendations::filter($request->all())
            ->selectRaw('cell_media_id, count(cell_media_id) as count')
            ->groupBy('cell_media_id')
            ->orderBy('count', 'DESC')
            ->orderBy('cell_media_id', 'ASC')
            ->limit(2)
            ->get()
            ->makeVisible('count');
        return $this->response->collection($most_cited, new CellMediaRecommendationsTransformer);
    }


    public function calculateConcentration(Request $request)
    {
        // TODO:Add logging for usage 
        $recommendation_aggregate = Recommendation::select('ingredient_id')
            ->selectRaw('ROUND(AVG(concentration_high),0) as avg_concentration_high, ROUND(AVG(concentration_mid),0) as avg_concentration_mid, ROUND(AVG(concentration_low),0) as avg_concentration_low')
            ->groupBy('ingredient_id')
            ->filter($request->all())
            ->get()
            ->makeVisible(['avg_concentration_high', 'avg_concentration_mid', 'avg_concentration_low']);
        return $this->response->collection($recommendation_aggregate, new RecommendationsTransformer);
    }

    public function geneProteinRecommendations(Request $request)
    {
        // TODO:Add logging for usage 
        $recommendation_aggregate = GpeRecommendation::filter($request->all())->select('gene_protein_expression_id', 'expression_type_id')
            ->groupBy('gene_protein_expression_id', 'expression_type_id')
            ->get();
        return $this->response->collection($recommendation_aggregate, new GpeRecommendationTransformer);
    }
}
